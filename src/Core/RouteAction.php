<?php
namespace Nano\Core;

trait RouteAction
{
	# ------------------------------------------ ------------------------------------------ #
	public final function action( $destination )
	{
		$destination = explode( '#', $destination );
		$controller_name = $destination[0];
		$action_name = $destination[1];
		$action_name = explode( '?', $action_name );
		
		if ( count( $action_name ) > 1 )
		{
			$querystring = $action_name[1];
			$querystring = explode( '&', $querystring );
			
			foreach ( $querystring as $item )
			{
				$item = explode( '=', $item );
				$key = $item[0];
				$value = $item[1];
				$_GET[$key] = $value;
			}
		}
		
		$action_name = $action_name[0];
		$controller = new $controller_name( $this->request, $this->dao, $this->args );
		$action_return = $controller->$action_name();
		
		if ( ! is_array( $action_return ) )
		{
			throw new \Nano\Exceptions\InvalidReturnException( 'Controller (' . $controller::class . ') return must be an array' );
		}
		
		foreach ( $action_return as $key => $value )
		{
			$this->args->add( $key, $value );
		}
		
		return $this;
	}
}
