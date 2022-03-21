<?php
namespace Nano\Helpers;

class Validation
{
	# ------------------------------------------ ------------------------------------------ #
	public static function validateStrings( $arguments )
	{
		$arguments = self::assureArgumentsIsArray( $arguments );
		
		foreach ( $arguments as $argument )
		{
			if ( ! $argument && $argument !== '0' )
			{
				$backtrace = debug_backtrace();
				throw new \InvalidArgumentException( 'Invalid string param at class: ' . $backtrace[1]['class'] . ', function: ' . $backtrace[1]['function'] );
			}
		}
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public static function validateObjects( $arguments )
	{
		$arguments = self::assureArgumentsIsArray( $arguments );
		
		foreach ( $arguments as $argument )
		{
			if ( ! $argument || ! is_object( $argument ) )
			{
				$backtrace = debug_backtrace();
				throw new \InvalidArgumentException( 'Invalid object param at class: ' . $backtrace[1]['class'] . ', function: ' . $backtrace[1]['function'] );
			}
		}
	}
	
	# ------------------------------------------ ------------------------------------------ #
	private static function assureArgumentsIsArray( $arguments )
	{
		if ( ! is_array( $arguments ) )
		{
			$arguments = [ $arguments ];
		}
		
		return $arguments;
	}
}