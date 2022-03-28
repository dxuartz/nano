<?php
namespace Nano\Helpers;

class MatchRegexUrl
{
	# ------------------------------------------ ------------------------------------------ #
	public static function do( $url, $request_url )
	{
		$regex = '/^' . str_replace( '/', '\/', $url );
		$regex = preg_replace( '/:([a-zA-Z0-9-_\.:]+)/', '(?P<$1>[A-Za-z0-9_\.:-]+)', $regex ) . '$/';
		$result = @preg_match( $regex, $request_url, $matches );
		return ( $result === 1 ? $matches : false );
	}
}
