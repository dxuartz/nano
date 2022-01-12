<?php
namespace Nano;
require __DIR__ . '/DbConnect.php';

class Db
{
	# ------------------------------------------ ------------------------------------------ #
	public static function getConnection( $config )
	{
		try
		{
			$conn_string  = 'mysql:';
			$conn_string .= 'host=' . $config['database']['host'] . ';';
			$conn_string .= 'dbname=' . $config['database']['name'] . ';';
			$conn_string .= 'port=' . $config['database']['port'] . ';';
			$conn_string .= 'charset=utf8';
			$conn = new \PDO( $conn_string, $config['database']['user'], $config['database']['pass'], array( \PDO::ATTR_PERSISTENT => false ) );
		}
		catch ( \Exception $e )
		{
			throw new \Nano\DbException( 'Error connecting to database: ' . $e->getMessage() );
			exit;
		}
		
		return $conn;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public static function setConnectionErrorMode( $error_mode, $error_mode_exception )
	{
		global $conn;
		$conn->setAttribute( $error_mode, $error_mode_exception );
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public static function setConnectionTimezone( $timezone )
	{
		global $conn;
		$conn->exec( "SET time_zone = '{$timezone}'" );
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public static function query( $sql, $out = [] )
	{
		global $conn;
		return $conn->query( $sql . ';' );
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public static function execute( $sql )
	{
		global $conn;
		
		try
		{
			$query = $conn->prepare( $sql );
			return $query->execute();
		}
		catch( \PDOException $e )
		{
			return false;
		}
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public static function getLastInsertId()
	{
		global $conn;
		return $conn->lastInsertId();
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public static function beginTransaction()
	{
		global $conn;
		return $conn->exec( 'BEGIN' );
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public static function rollbackTransaction()
	{
		global $conn;
		return $conn->exec( 'ROLLBACK' );
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public static function commitTransaction()
	{
		global $conn;
		return $conn->exec( 'COMMIT' );
	}
}