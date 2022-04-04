<?php
namespace Nano\Core;

trait RouteMatch
{
	# ------------------------------------------ ------------------------------------------ #
	private function match( $url )
	{
		$matches = \Nano\Helpers\MatchRegexUrl::do( $url, $this->url );
		
		if ( $matches === false )
		{
			return ( new \Nano\Core\RouteVoid() );
		}
		
		$this->has_match = true;
		$this->getUrlVariables( $matches );
		$this->runQueuedMiddlewares();
		return $this;
	}
}