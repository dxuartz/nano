<?php
namespace Nano;

class HttpRequest
{
	public $get = [];
	public $post = [];
	public $put = [];
	
	# ------------------------------------------ ------------------------------------------ #
	public final static function initialize()
	{
		$httpRequest = new self();
		$httpRequest->populateGet();
		$httpRequest->populatePost();
		$httpRequest->populatePut();
		return $httpRequest;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function populateObject( $object, $method )
	{
		foreach ( get_object_vars( $object ) as $key => $value )
		{
			if ( isset( $this->$method[$key] ) )
			{
				$object->$key = $this->$method[$key];
			}
		}
	}
	
	# ------------------------------------------ ------------------------------------------ #
	private function populateGet()
	{
		$this->get = $_GET;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	private function populatePost()
	{
		$this->post = $_POST;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	private function populatePut()
	{
		if ( isset( $_SERVER['CONTENT_TYPE'] ) && $_SERVER['CONTENT_TYPE'] )
		{
			$this->put = $this->parseRawHttpRequest();
		}
	}
	
	# ------------------------------------------ ------------------------------------------ #
	private function parseRawHttpRequest()
	{
		$a_data = [];
		$input = file_get_contents( 'php://input' );
		preg_match( '/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches );
		$boundary = $matches[1];
		$a_blocks = preg_split( "/-+$boundary/", $input );
		array_pop( $a_blocks );
		
		foreach ( $a_blocks as $id => $block )
		{
			if ( empty( $block ) )
			{
				continue;
			}
			
			if ( strpos( $block, 'application/octet-stream' ) !== FALSE )
			{
				preg_match( "/name=\"([^\"]*)\".*stream[\n|\r]+([^\n\r].*)?$/s", $block, $matches );
			}
			else
			{
				preg_match( '/name=\"([^\"]*)\"[\n|\r]+([^\n\r].*)?\r$/s', $block, $matches );
			}
			
			$a_data[$matches[1]] = $matches[2];
		}
		
		return $a_data;
	}
}
