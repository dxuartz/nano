<?php
namespace Nano\Core;

trait RouteMiddleware
{
	# ------------------------------------------ ------------------------------------------ #
	public final function addMiddleware( $class_name )
	{
		array_push( $this->middlewares_queue, $class_name );
		return $this;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function middleware( $class_name )
	{
		if ( ! $this->has_match )
		{
			throw new \Nano\Exceptions\InvalidExecutionException( 'Middleware (' . $class_name . ') can only run after URL match' );
		}
		
		$middleware = new $class_name( $this->request, $this->dao, $this->args );
		$result = $middleware->handle();
		
		if ( ! is_array( $result ) )
		{
			throw new \Nano\Exceptions\InvalidReturnException( 'Middleware (' . $middleware::class . ') return must be an array' );
		}
		
		foreach ( $result as $key => $value )
		{
			$this->args->add( $key, $value );
		}
		
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
