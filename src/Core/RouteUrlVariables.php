<?php
namespace Nano\Core;

trait RouteUrlVariables
{
	# ------------------------------------------ ------------------------------------------ #
	private function getUrlVariables( $matches )
	{
		foreach ( $matches as $key => $value )
		{
			if ( is_string( $key ) )
			{
				$this->request->add( $key, $value, 'params' );
			}
		}
	}
}
