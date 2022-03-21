<?php
namespace Nano\Helpers;

class QuoteUnquote
{
	# ------------------------------------------ ------------------------------------------ #
	public static function unquoteRow( $row )
	{
		foreach ( $row as $key => $value )
		{
			$row->$key = \Nano\Helpers\QuoteUnquote::unquoteString( $value );
		}
		
		return $row;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	private static function unquoteString( $value, $preserve_quotes = false )
	{
		if ( $value === null )
		{
			return null;
		}
		
		$value = str_replace( '\n', '', $value );
		$value = str_replace( '\r', "\r", $value );
		
		if ( $preserve_quotes )
		{
			$value = str_replace( '\"', '"', $value );
			$value = str_replace( "\'", "'", $value );
		}
		else
		{
			$value = str_replace( '\"', '”', $value );
			$value = str_replace( "\'", "’", $value );
		}
		
		return $value;
	}
}