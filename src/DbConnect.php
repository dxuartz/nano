<?php
namespace Nano;
global $conn;

if ( ! isset( $conn ) || ! $conn )
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
	
	if ( ! isset( $config ) || ! isset( $config['database'] ) )
	{
		die( 'Configuration values not found. Please check your nano.conf.ini file and try again.' );
	}
	
	try
	{
		$conn = Db::getConnection( $config );
		Db::setConnectionErrorMode( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
		Db::setConnectionTimezone( $config['database']['timezone'] );
	}
	catch ( \Nano\DbException $e )
	{
		die( $e->getMessage() );
	}
}
