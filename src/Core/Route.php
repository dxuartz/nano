<?php
namespace Nano\Core;

class Route
{
	private static $instances = [];
	private $dao = null;
	private $request = null;
	private $request_method = null;
	private $has_match = false;
	private $request_url = null;
	private $middlewares_queue = [];
	private $args = null;
	private $view_path = null;
	private $view = null;
	private $layout = null;
	use \Nano\Core\RouteAction;
	use \Nano\Core\RouteMethods;
	use \Nano\Core\RouteMiddleware;
	use \Nano\Core\RouteView;
	use \Nano\Core\RouteUrlVariables;
	
	# ------------------------------------------ ------------------------------------------ #
	protected function __construct()
	{
		$this->view_path = __DIR__ . '/../../../../../';
		$this->request_method = $_SERVER['REQUEST_METHOD'] ?? '';
		$this->args = new \Nano\Core\Arguments();
		$this->dao = new \Nano\Core\Dao( new \Nano\Core\Db() );
		$this->request = \Nano\Core\HttpRequest::initialize();
	}
	
	# ------------------------------------------ ------------------------------------------ #
	protected function __clone()
	{
		//
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
		$this->request_method = $request_method;
		return $this;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function setRequestUrl( $url )
	{
		$this->request_url = $url;
		return $this;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function setViewPath( $path )
	{
		$this->view_path = $path;
		return $this;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function setLayout( $layout )
	{
		$this->layout = $layout;
		return $this;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	private function match( $url )
	{
		$matches = \Nano\Helpers\MatchRegexUrl::do( $url, $this->request_url );
		
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
