<?php
namespace Nano;

class Partial
{
	# ------------------------------------------ ------------------------------------------ #
	public static function get( $path, $content )
	{
		foreach ( $content as $content_item_key_name => $content_item_value )
		{
			$$content_item_key_name = $content[$content_item_key_name];
		}
		
		ob_start();
		$partial = file_get_contents( $path );
		$partial = eval( "?> $partial" );
		$partial = ob_get_contents();
		ob_end_clean();
		return $partial;
	}
}
