<?php
namespace Nano\Core;

class Dao
{
	private $db;
	private const SQL_QUERY_COUNT_TRUE = true;
	private const SQL_QUERY_ORDER_NULL = null;
	private const SQL_QUERY_LIMIT_NULL = null;
	
	# ------------------------------------------ ------------------------------------------ #
	public function __construct( $db )
	{
		$this->db = $db;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function create( $class_name )
	{
		return new $class_name();
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function find( $class_name, $where = null )
	{
		\Nano\Helpers\Validation::validateStrings( [ $class_name ] );
		
		if ( is_numeric( $where ) )
		{
			$where = "`id` = '{$where}'";
		}
		
		$table_name = $this->getTableName( $class_name );
		$sql = $this->getSqlQuery( $table_name, $where, self::SQL_QUERY_ORDER_NULL, 1 );
		$result_set = $this->db->query( $sql );
		$result_set = $this->fetchResultSet( $result_set );
		$out_object = new $class_name();
		$out_object = $this->populateObject( $out_object, $result_set );
		return $out_object;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function findAll( $class_name, $where = null, $order_by = null, $limit = null )
	{
		\Nano\Helpers\Validation::validateStrings( [ $class_name ] );
		$out = [];
		$table_name = $this->getTableName( $class_name );
		$sql = $this->getSqlQuery( $table_name, $where, $order_by, $limit );
		$result_set = $this->db->query( $sql );
		$result_set = $this->fetchResultSet( $result_set );
		
		foreach ( $result_set as $row )
		{
			$object = new $class_name();
			$object = $this->populateObject( $object, $row );
			$out[] = $object;
		}
		
		return $out;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function findParent( $class_name, $object )
	{
		\Nano\Helpers\Validation::validateStrings( [ $class_name ] );
		\Nano\Helpers\Validation::validateObjects( [ $object ] );
		$field_name = $this->getParentFieldName( $class_name );
		return $this->find( $class_name, $object->$field_name );
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function count( $class_name, $where = null )
	{
		\Nano\Helpers\Validation::validateStrings( [ $class_name ] );
		$table_name = $this->getTableName( $class_name );
		$sql = $this->getSqlQuery( $table_name, $where, self::SQL_QUERY_ORDER_NULL, self::SQL_QUERY_LIMIT_NULL, self::SQL_QUERY_COUNT_TRUE );
		$result_set = $this->db->query( $sql );
		$result_set = $this->fetchResultSet( $result_set );
		
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
	public final function query( $sql )
	{
		\Nano\Helpers\Validation::validateStrings( [ $sql ] );
		$result_set = $this->db->query( $sql );
		$result_set = $this->fetchResultSet( $result_set );
		return $result_set;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function execute( $sql )
	{
		\Nano\Helpers\Validation::validateStrings( [ $sql ] );
		$result = $this->db->execute( $sql );
		return $result;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function save( $object, $error_message = 'Error saving object', $debug_mode = false )
	{
		\Nano\Helpers\Validation::validateObjects( [ $object ] );
		$sql = $this->getSqlSave( $object );
		
		if ( $debug_mode )
		{
			return $sql;
		}
		
		$result = $this->execute( $sql );
		
		if ( ! $result )
		{
			throw new \Nano\Exceptions\DaoException( $error_message );
		}
		
		if ( ! $object->id )
		{
			$object->id = $this->db->getLastInsertId();
		}
		
		if ( ! $object->created_at )
		{
			$object->created_at = date( 'Y-m-d H:i:s' );
		}
		
		$object->updated_at = date( 'Y-m-d H:i:s' );
		return $object;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function delete( $object, $error_message = 'Error deleting object' )
	{
		\Nano\Helpers\Validation::validateObjects( [ $object ] );
		$class_name = get_class( $object );
		$table_name = $this->getTableName( $class_name );
		$result = $this->execute( "DELETE FROM {$table_name} WHERE `id` = '{$object->id}' LIMIT 1" );
		
		if ( ! $result )
		{
			throw new \Nano\Exceptions\DaoException( $error_message );
		}
		
		return $object;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	private function fetchResultSet( $result_set )
	{
		$out = [];
		
		while ( $row = $result_set->fetchObject() )
		{
			$out[] = \Nano\Helpers\QuoteUnquote::unquoteRow( $row );
		}
		
		return $out;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	private function getTableName( $class_name )
	{
		$table_name = explode( '\\', $class_name );
		$table_name = end( $table_name );
		$table_name = \Nano\Helpers\CaseTransform::camelcaseToUnderscore( $table_name );
		$table_name = \Nano\Helpers\SingularAndPlural::convertToPlural( $table_name );
		return $table_name;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	private function getParentFieldName( $class_name )
	{
		$table_name = explode( '\\', $class_name );
		$table_name = end( $table_name );
		$table_name = \Nano\Helpers\CaseTransform::camelcaseToUnderscore( $table_name );
		return $table_name . '_id';
	}
	
	# ------------------------------------------ ------------------------------------------ #
	private function populateObject( $object, $data )
	{
		if ( is_array( $data ) )
		{
			$data = reset( $data );
		}
		
		if ( $data )
		{
			foreach ( get_class_vars( get_class( $object ) ) as $key => $value )
			{
				if ( is_numeric( $data->$key ) )
				{
					$object->$key = ( $data->$key == ( int ) $data->$key ) ? ( int ) $data->$key : ( float ) $data->$key;
				}
				else
				{
					$object->$key = $data->$key;
				}
			}
		}
		
		return $object;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	private function getSqlQuery( $table_name, $where = null, $order_by = null, $limit = null, $count = false )
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
	private function getSqlSave( $object )
	{
		$class_name = get_class( $object );
		$table_name = $this->getTableName( $class_name );
		
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
