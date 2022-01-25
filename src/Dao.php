<?php
namespace Nano;

class Dao
{
	private const SQL_QUERY_COUNT_TRUE = true;
	private const SQL_QUERY_ORDER_NULL = null;
	private const SQL_QUERY_LIMIT_NULL = null;
	
	# ------------------------------------------ ------------------------------------------ #
	public final static function create( $class_name )
	{
		return new $class_name();
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final static function find( $class_name, $where = null )
	{
		Validation::validateStrings( [ $class_name ] );
		
		if ( is_numeric( $where ) )
		{
			$where = "`id` = '{$where}'";
		}
		
		$table_name = Dao::getTableName( $class_name );
		$sql = Dao::getSqlQuery( $table_name, $where, self::SQL_QUERY_ORDER_NULL, 1 );
		$result_set = Db::query( $sql );
		$result_set = Dao::fetchResultSet( $result_set );
		$out_object = new $class_name();
		$out_object = Dao::populateObject( $out_object, $result_set );
		return $out_object;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final static function findAll( $class_name, $where = null, $order_by = null, $limit = null )
	{
		Validation::validateStrings( [ $class_name ] );
		$out = [];
		$table_name = Dao::getTableName( $class_name );
		$sql = Dao::getSqlQuery( $table_name, $where, $order_by, $limit );
		$result_set = Db::query( $sql );
		$result_set = Dao::fetchResultSet( $result_set );
		
		foreach ( $result_set as $row )
		{
			$object = new $class_name();
			$object = Dao::populateObject( $object, $row );
			$out[] = $object;
		}
		
		return $out;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final static function findParent( $class_name, $object )
	{
		Validation::validateStrings( [ $class_name ] );
		Validation::validateObjects( [ $object ] );
		$field_name = Dao::getParentFieldName( $class_name );
		return Dao::find( $class_name, $object->$field_name );
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final static function count( $class_name, $where = null )
	{
		Validation::validateStrings( [ $class_name ] );
		$table_name = Dao::getTableName( $class_name );
		$sql = Dao::getSqlQuery( $table_name, $where, self::SQL_QUERY_ORDER_NULL, self::SQL_QUERY_LIMIT_NULL, self::SQL_QUERY_COUNT_TRUE );
		$result_set = Db::query( $sql );
		$result_set = Dao::fetchResultSet( $result_set );
		
		if ( ! empty( $result_set ) )
		{
			return $result_set[0]->q;
		}
		else
		{
			return 0;
		}
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final static function query( $sql )
	{
		Validation::validateStrings( [ $sql ] );
		$result_set = Db::query( $sql );
		$result_set = Dao::fetchResultSet( $result_set );
		return $result_set;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final static function execute( $sql )
	{
		Validation::validateStrings( [ $sql ] );
		$result = Db::execute( $sql );
		return $result;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final static function save( $object, $error_message = 'Error saving object', $debug_mode = false )
	{
		Validation::validateObjects( [ $object ] );
		$sql = Dao::getSqlSave( $object );
		
		if ( $debug_mode )
		{
			return $sql;
		}
		
		$result = Dao::execute( $sql );
		
		if ( ! $result )
		{
			throw new \Nano\DaoException( $error_message );
		}
		
		if ( ! $object->id )
		{
			$object->id = \Nano\Db::getLastInsertId();
		}
		
		if ( ! $object->created_at )
		{
			$object->created_at = date( 'Y-m-d H:i:s' );
		}
		
		$object->updated_at = date( 'Y-m-d H:i:s' );
		return $object;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final static function delete( $object )
	{
		Validation::validateObjects( [ $object ] );
		$class_name = get_class( $object );
		$table_name = Dao::getTableName( $class_name );
		$result = Dao::execute( "DELETE FROM {$table_name} WHERE `id` = '{$object->id}' LIMIT 1" );
		
		if ( ! $result )
		{
			throw new \Nano\DaoException( 'Error deleting object' );
		}
		
		return $object;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	private static function fetchResultSet( $result_set )
	{
		$out = [];
		
		while ( $row = $result_set->fetchObject() )
		{
			$out[] = \Nano\QuoteUnquote::unquoteRow( $row );
		}
		
		return $out;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	private static function getTableName( $class_name )
	{
		$table_name = explode( '\\', $class_name );
		$table_name = end( $table_name );
		$table_name = \Nano\CaseTransform::camelcaseToUnderscore( $table_name );
		$table_name = \Nano\SingularAndPlural::convertToPlural( $table_name );
		return $table_name;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	private static function getParentFieldName( $class_name )
	{
		$table_name = explode( '\\', $class_name );
		$table_name = end( $table_name );
		$table_name = \Nano\CaseTransform::camelcaseToUnderscore( $table_name );
		return $table_name . '_id';
	}
	
	# ------------------------------------------ ------------------------------------------ #
	private static function populateObject( $object, $data )
	{
		if ( is_array( $data ) )
		{
			$data = reset( $data );
		}
		
		if ( $data )
		{
			foreach ( get_class_vars( get_class( $object ) ) as $key => $value )
			{
				$object->$key = $data->$key;
			}
		}
		
		return $object;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	private static function getSqlQuery( $table_name, $where = null, $order_by = null, $limit = null, $count = false )
	{
		$sql  = "SELECT ";
		
		if ( $count )
		{
			$sql .= "COUNT( * ) AS `q` ";
		}
		else
		{
			$sql .= "* ";
		}
		
		$sql .= "FROM {$table_name} ";
		
		if ( $where )
		{
			$sql .= "WHERE {$where} ";
		}
		
		if ( $order_by )
		{
			$sql .= "ORDER BY {$order_by} ";
		}
		
		if ( $limit )
		{
			$sql .= "LIMIT {$limit} ";
		}
		
		return $sql;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	private static function getSqlSave( $object )
	{
		$class_name = get_class( $object );
		$table_name = Dao::getTableName( $class_name );
		
		foreach ( get_class_vars( $class_name ) as $key => $value )
		{
			if ( $key == 'created_at' && ! $object->id )
			{
				$keys_values[] = "`created_at` = '" . date( 'Y-m-d H:i:s' ) . "'";
			}
			elseif ( $key == 'updated_at' )
			{
				$keys_values[] = "`updated_at` = '" . date( 'Y-m-d H:i:s' ) . "'";
			}
			elseif ( property_exists( $object, $key ) && $object->$key !== null && $object->$key !== 'null' )
			{
				$keys_values[] = "`{$key}` = '{$object->$key}'";
			}
			elseif ( property_exists( $object, $key ) && $object->$key === 'null' )
			{
				$keys_values[] = "`{$key}` = NULL";
			}
		}
		
		$keys_values = implode( ',', $keys_values );
		
		if ( $object->id )
		{
			return "UPDATE `{$table_name}` SET {$keys_values} WHERE `id` = '{$object->id}'";
		}
		else
		{
			return "INSERT INTO `{$table_name}` SET {$keys_values}";
		}
		
	}
}
