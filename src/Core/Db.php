<?php
namespace Nano\Core;

class Db
{
	private $conn;
	
	# ------------------------------------------ ------------------------------------------ #
	public function __construct()
	{
		$config = $this->getConfig();
		
		try
		{
			$conn_string  = 'mysql:';
			$conn_string .= 'host=' . $config['database']['host'] . ';';
			$conn_string .= 'dbname=' . $config['database']['name'] . ';';
			$conn_string .= 'port=' . $config['database']['port'] . ';';
			$conn_string .= 'charset=utf8';
			$this->conn = new \PDO( $conn_string, $config['database']['user'], $config['database']['pass'], array( \PDO::ATTR_PERSISTENT => false ) );
			$this->setConnectionErrorMode( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
			$this->setConnectionTimezone( $config['database']['timezone'] );
		}
		catch ( \Exception $e )
		{
			throw new \Nano\Exceptions\DbException( 'Error connecting to database: ' . $e->getMessage() );
			exit;
		}
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function setConnectionErrorMode( $error_mode, $error_mode_exception )
	{
		$this->conn->setAttribute( $error_mode, $error_mode_exception );
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function setConnectionTimezone( $timezone )
	{
		$this->conn->exec( "SET time_zone = '{$timezone}'" );
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function query( $sql, $out = [] )
	{
		return $this->conn->query( $sql . ';' );
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function execute( $sql )
	{
		try
		{
			$query = $this->conn->prepare( $sql );
			return $query->execute();
		}
		catch( \PDOException $e )
		{
			return false;
		}
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function getLastInsertId()
	{
		return $this->conn->lastInsertId();
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function beginTransaction()
	{
		return $this->conn->exec( 'BEGIN' );
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function rollbackTransaction()
	{
		return $this->conn->exec( 'ROLLBACK' );
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function commitTransaction()
	{
		return $this->conn->exec( 'COMMIT' );
	}
	
	# ------------------------------------------ ------------------------------------------ #
	private function getConfig()
	{
		for ( $i = 1; $i <= 6; $i++ )
		{
			$levels_down = str_repeat( '/..', $i );
			
			if ( file_exists( __DIR__ . $levels_down . '/nano.conf.ini' ) )
			{
				$config = parse_ini_file( __DIR__ . $levels_down . '/nano.conf.ini', true );
				break;
			}
		}
		
		if ( ! isset( $config ) )
		{
			die( 'Configuration file nano.conf.ini not found.' );
		}
		
		if ( ! isset( $config['database'] ) )
		{
			die( 'Configuration values not found. Please check your nano.conf.ini file and try again.' );
		}
		
		if ( ! isset( $config['database']['host'] ) || ! $config['database']['host'] )
		{
			die( 'Configuration missing param: host. Please check your nano.conf.ini file and try again.' );
		}
		
		if ( ! isset( $config['database']['user'] ) || ! $config['database']['user'] )
		{
			die( 'Configuration missing param: user. Please check your nano.conf.ini file and try again.' );
		}
		
		if ( ! isset( $config['database']['pass'] ) || ! $config['database']['pass'] )
		{
			die( 'Configuration missing param: pass. Please check your nano.conf.ini file and try again.' );
		}
		
		if ( ! isset( $config['database']['name'] ) || ! $config['database']['name'] )
		{
			die( 'Configuration missing param: name. Please check your nano.conf.ini file and try again.' );
		}
		
		if ( ! isset( $config['database']['port'] ) || ! $config['database']['port'] )
		{
			die( 'Configuration missing param: port. Please check your nano.conf.ini file and try again.' );
		}
		
		if ( ! isset( $config['database']['timezone'] ) || ! $config['database']['timezone'] )
		{
			die( 'Configuration missing param: timezone. Please check your nano.conf.ini file and try again.' );
		}
		
		return $config;
	}
}
