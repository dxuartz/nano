<?php
namespace Nano\Core;
use stdClass;

class Response
{
	public readonly stdClass $data;
	
	# ------------------------------------------ ------------------------------------------ #
	public function __construct()
	{
		$this->data = new stdClass();
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public function __get( string $key )
	{
		if ( isset( $this->data->$key ) )
		{
			return $this->data->$key;
		}
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function add( string $key, mixed $value ) : self
	{
		if ( ! is_string( $key ) )
		{
			throw new \Nano\Exceptions\InvalidInputException( 'Response key must be a string' );
		}
		
		$this->data->$key = $value;
		return $this;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function list() : object
	{
		return $this->data;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function remove( string $key ) : self
	{
		if ( isset( $this->data->$key ) )
		{
			unset( $this->data->$key );
		}
		
		return $this;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function clear() : self
	{
		foreach ( $this->data as $key => $_ )
		{
			unset( $this->data->$key );
		}
		
		return $this;
	}
}
