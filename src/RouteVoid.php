<?php
namespace Nano;

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
	public function action( $destination )
	{
		return $this;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function view( $destination )
	{
		return $this;
	}
}