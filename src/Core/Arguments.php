<?php
namespace Nano\Core;

class Arguments
{
	private $args = [];
	
	# ------------------------------------------ ------------------------------------------ #
	public function __construct()
	{
		
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function add( string $key, mixed $value ) : void
	{
		if ( ! is_string( $key ) )
		{
			throw new \Nano\Exceptions\InvalidInputException( 'Content key must be a string' );
		}
		
		$this->args[$key] = $value;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function remove( string $key ) : void
	{
		if ( isset( $this->args[$key] ) )
		{
			unset( $this->args[$key] );
		}
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function get( string $key ) : mixed
	{
		return $this->args[$key] ?? null;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function toArray()
	{
		return $this->args;
	}
}
