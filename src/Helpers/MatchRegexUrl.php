<?php
namespace Nano\Helpers;

class MatchRegexUrl
{
	# ------------------------------------------ ------------------------------------------ #
	public static function do( $pattern, $url )
	{
		$regex = '/^' . str_replace( '/', '\/', $pattern );
		$regex = preg_replace( '/:([a-zA-Z0-9-_\.:]+)/', '(?P<$1>[A-Za-z0-9_\.:-]+)', $regex ) . '$/';
		$result = @preg_match( $regex, $url, $matches );
		return ( $result === 1 ? $matches : false );
	}
}
