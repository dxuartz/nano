<?php
namespace Nano\Core;

trait RouteMiddleware
{
	# ------------------------------------------ ------------------------------------------ #
	public final function middleware( $class_name )
	{
		if ( ! $this->has_match )
		{
			throw new \Nano\Exceptions\InvalidExecutionException( 'Middleware (' . $class_name . ') can only run after URL match' );
		}
		
		$this->response->clear();
		$middleware = new $class_name( $this->request, $this->response, $this->dao, $this->args );
		$middleware_response = $middleware->handle();
		
		if ( gettype( $middleware_response ) != 'object' || get_class( $middleware_response ) != 'Nano\Core\Response' )
		{
			throw new \Nano\Exceptions\InvalidReturnException( 'Middleware (' . $middleware::class . ') return must be of type \Nano\Core\Response' );
		}
		
		$this->response = $middleware_response;
		
		foreach ( $this->response->list() as $key => $value )
		{
			$this->args->add( $key, $value );
		}
		
		return $this;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function pushMiddleware( $class_name )
	{
		array_push( $this->middlewares_queue, $class_name );
		return $this;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	private function runQueuedMiddlewares()
	{
		foreach ( $this->middlewares_queue as $middleware_class_name )
		{
			$this->middleware( $middleware_class_name );
		}
		
		$this->middlewares_queue = [];
	}
}
