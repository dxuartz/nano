<?php
namespace Nano\Core;

trait RouteMethods
{
	# ------------------------------------------ ------------------------------------------ #
	public final function get( $url )
	{
		if ( strtolower( $this->request_method ) != 'get' )
		{
			return ( new \Nano\Core\RouteVoid() );
		}
		
		return $this->match( $url );
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function post( $url )
	{
		if ( strtolower( $this->request_method ) != 'post' )
		{
			return ( new \Nano\Core\RouteVoid() );
		}
		
		return $this->match( $url );
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function put( $url )
	{
		if ( strtolower( $this->request_method ) != 'put' )
		{
			return ( new \Nano\Core\RouteVoid() );
		}
		
		return $this->match( $url );
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function delete( $url )
	{
		if ( strtolower( $this->request_method ) != 'delete' )
		{
			return ( new \Nano\Core\RouteVoid() );
		}
		
		return $this->match( $url );
	}
}