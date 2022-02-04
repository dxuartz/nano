<?php
namespace Nano;
use stdClass;

class Route
{
	private static $instances = [];
	private $request_method = null;
	private $url = null;
	private $content = null;
	private $view_path = null;
	private $view = null;
	private $layout = null;
	
	# ------------------------------------------ ------------------------------------------ #
	protected function __construct()
	{
		$this->content = new stdClass();
		$this->view_path = __DIR__ . '/../../../';
	}
	
	# ------------------------------------------ ------------------------------------------ #
	protected function __clone()
	{
		
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public function __wakeup()
	{
		throw new \Exception( 'Cannot unserialize a singleton' );
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public static function getInstance()
	{
		$cls = static::class;
		
		if ( ! isset( self::$instances[$cls] ) )
		{
			self::$instances[$cls] = new static();
		}
		
		return self::$instances[$cls];
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function setRequestMethod( $request_method )
	{
		if ( get_class( $this ) == 'Nano\RouteVoid' )
		{
			return $this;
		}
		
		$this->request_method = $request_method;
		return $this;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function setUrl( $url )
	{
		if ( get_class( $this ) == 'Nano\RouteVoid' )
		{
			return $this;
		}
		
		$this->url = $url;
		return $this;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function setViewPath( $path )
	{
		if ( get_class( $this ) == 'Nano\RouteVoid' )
		{
			return $this;
		}
		
		$this->view_path = $path;
		return $this;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function getViewPath()
	{
		return $this->view_path;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function setLayout( $layout )
	{
		if ( get_class( $this ) == 'Nano\RouteVoid' )
		{
			return $this;
		}
		
		$this->layout = $layout;
		return $this;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function get( $url )
	{
		if ( strtolower( $this->request_method ) != 'get' )
		{
			return ( new \Nano\RouteVoid() );
		}
		
		return $this->match( $url );
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function post( $url )
	{
		if ( strtolower( $this->request_method ) != 'post' )
		{
			return ( new \Nano\RouteVoid() );
		}
		
		return $this->match( $url );
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function put( $url )
	{
		if ( strtolower( $this->request_method ) != 'put' )
		{
			return ( new \Nano\RouteVoid() );
		}
		
		return $this->match( $url );
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function delete( $url )
	{
		if ( strtolower( $this->request_method ) != 'delete' )
		{
			return ( new \Nano\RouteVoid() );
		}
		
		return $this->match( $url );
	}
	
	# ------------------------------------------ ------------------------------------------ #
	private function match( $url )
	{
		$matches = $this->matchRegexUrl( $url );
		
		if ( $matches === false )
		{
			return ( new \Nano\RouteVoid() );
		}
		
		$this->getUrlVariables( $matches );
		return $this;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public function action( $destination )
	{
		if ( get_class( $this ) == 'Nano\RouteVoid' )
		{
			return $this;
		}
		
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
		
		$httpRequest = \Nano\HttpRequest::initialize();
		$action_name = $action_name[0];
		$controller = new $controller_name();
		$action_return = $controller->$action_name( $httpRequest );
		
		if ( is_array( $action_return ) )
		{
			foreach ( $action_return as $key => $value )
			{
				$this->content->$key = $value;
			}
		}
		
		if ( isset( $this->content->layout ) && $this->content->layout )
		{
			$this->setLayout( $this->content->layout );
		}
		
		return $this;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function view( $destination )
	{
		if ( get_class( $this ) == 'Nano\RouteVoid' )
		{
			return $this;
		}
		
		$this->view = $destination;
		
		foreach ( $this->content as $content_var_name => $content_var_value )
		{
			$$content_var_name = $this->content->$content_var_name;
		}
		
		if ( isset( $this->content->layout ) && $this->content->layout )
		{
			$this->setLayout( $this->content->layout );
		}
		
		ob_start();
		include( $this->view_path . $this->view );
		$view = ob_get_contents();
		ob_end_clean();
		
		if ( $this->layout )
		{
			ob_start();
			include( $this->layout );
			$html = ob_get_contents();
			ob_end_clean();
		}
		else
		{
			$html = $view;
		}
		
		echo $html;
		exit;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	private function matchRegexUrl( $url )
	{
		$regex = '/^' . str_replace( '/', '\/', $url );
		$regex = preg_replace( '/:([a-zA-Z0-9-_\.:]+)/', '(?P<$1>[A-Za-z0-9-_\.:]+)', $regex ) . '$/';
		$result = @preg_match( $regex, $this->url, $matches );
		return ( $result === 1 ? $matches : false );
	}
	
	# ------------------------------------------ ------------------------------------------ #
	private function getUrlVariables( $matches )
	{
		foreach ( $matches as $key => $value )
		{
			if ( ! is_numeric( $key ) )
			{
				$_GET[$key] = $value;
			}
		}
	}
}