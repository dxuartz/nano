<?php
namespace Nano\Core;

class RouteVoid
{
	# ------------------------------------------ ------------------------------------------ #
	public final function setRequestMethod( $request_method )
	{
		return $this;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function setUrl( $url )
	{
		return $this;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function setViewPath( $path )
	{
		return $this;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function setLayout( $layout )
	{
		return $this;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function addMiddleware( $class_name )
	{
		return $this;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function middleware( $class_name )
	{
		return $this;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function action( $destination )
	{
		return $this;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function view( $destination )
	{
		return $this;
	}
}
