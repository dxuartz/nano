<?php
namespace Nano\Core;

class Route
{
	private static $instances = [];
	private $method = null;
	private $url = null;
	private $request = null;
	private $response = null;
	private $dao = null;
	private $args = null;
	private $has_match = false;
	private $middlewares_queue = [];
	private $view_path = null;
	private $view = null;
	private $layout = null;
	use \Nano\Core\RouteAction;
	use \Nano\Core\RouteMatch;
	use \Nano\Core\RouteMethods;
	use \Nano\Core\RouteMiddleware;
	use \Nano\Core\RouteUrlVariables;
	use \Nano\Core\RouteView;
	
	# ------------------------------------------ ------------------------------------------ #
	protected function __construct()
	{
		$this->view_path = __DIR__ . '/../../../../../';
		$this->method = $_SERVER['REQUEST_METHOD'] ?? '';
		$this->args = new \Nano\Core\Arguments();
		$this->dao = new \Nano\Core\Dao( new \Nano\Core\Db() );
		$this->request = new \Nano\Core\Request();
		$this->response = new \Nano\Core\Response();
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
	public final function setUrl( $url )
	{
		$this->url = $url;
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
}
