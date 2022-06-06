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
		
		$this->response->clear();
		$controller = new $controller_name( $this->request, $this->response, $this->dao, $this->args );
		$action_name = $action_name[0];
		$action_response = $controller->$action_name();
		
		if ( gettype( $action_response ) != 'object' || get_class( $action_response ) != 'Nano\Core\Response' )
		{
			throw new \Nano\Exceptions\InvalidReturnException( 'Controller (' . $controller::class . ') return must be of type \Nano\Core\Response' );
		}
		
		$this->args->add( 'controller_name', $controller_name );
		$this->args->add( 'action_name', $action_name );
		$this->response = $action_response;
		
		foreach ( $this->response->list() as $key => $value )
		{
			$this->args->add( $key, $value );
		}
		
		return $this;
	}
}
