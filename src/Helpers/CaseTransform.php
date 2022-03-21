<?php
namespace Nano\Helpers;

class CaseTransform
{
	# ------------------------------------------ ------------------------------------------ #
	public static function camelcaseToUnderscore( $input )
	{
		$out = ltrim( strtolower( preg_replace( '/[A-Z]/', '_$0', $input ) ), '_' );
		
		if ( substr( $out, 0, 1 ) == '_' )
		{
			$out = substr( $out, 1 );
		}
		
		if ( substr( $out, 0, 2 ) == '\_' )
		{
			$out = substr( $out, 2 );
		}
		
		return $out;
	}
}