<?php
namespace Nano\Core;
use stdClass;

class Request
{
	public readonly stdClass $headers;
	public readonly stdClass $query;
	public readonly stdClass $params;
	public readonly stdClass $body;
	
	# ------------------------------------------ ------------------------------------------ #
	public function __construct()
	{
		$this->headers = new stdClass();
		$this->query = new stdClass();
		$this->params = new stdClass();
		$this->body = new stdClass();
		$this->populate();
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function add( string $key, mixed $value, string $type ) : bool
	{
		if ( ! in_array( $type, [ 'headers', 'query', 'params', 'body' ] ) )
		{
			return false;
		}
		
		$this->$type->$key = $value;
		return true;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	private function populate() : void
	{
		$this->populateHeaders();
		$this->populateQuery();
		$this->populateBodyFromFormData();
		$this->populateBodyFromRawJsonData();
		$this->populateBodyFromMultipartFormData();
	}
	
	# ------------------------------------------ ------------------------------------------ #
	private function populateHeaders() : bool
	{
		foreach ( getallheaders() as $key => $value )
		{
			$key = strtolower( $key );
			$key = str_replace( '-', '_', $key );
			$this->add( $key, $value, 'headers' );
		}
		
		return true;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	private function populateQuery() : bool
	{
		foreach ( $_GET as $key => $value )
		{
			$this->add( $key, $value, 'query' );
		}
		
		return true;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	private function populateBodyFromFormData() : bool
	{
		foreach ( $_POST as $key => $value )
		{
			$this->add( $key, $value, 'body' );
		}
		
		return true;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	private function populateBodyFromRawJsonData() : bool
	{
		$input = file_get_contents( 'php://input' );
		
		if ( ! $input )
		{
			return false;
		}
		
		$json_body = json_decode( $input, false );
		
		if ( json_last_error() )
		{
			return false;
		}
		
		$this->destructureJsonToBody( $json_body );
		return true;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	private function populateBodyFromMultipartFormData() : bool
	{
		$input = file_get_contents( 'php://input' );
		
		if ( ! $input )
		{
			return false;
		}
		
		preg_match( '/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches );
		
		if ( empty( $matches ) )
		{
			return false;
		}
		
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
			
			$this->add( $matches[1], $matches[2] ?? '', 'body' );
		}
		
		return true;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	private function destructureJsonToBody( $json_body )
	{
		if ( is_object( $json_body ) )
		{
			foreach ( $json_body as $key => $value )
			{
				$this->add( $key, $value, 'body' );
			}
		}
		elseif ( is_array( $json_body ) )
		{
			$this->add( 'array', $json_body, 'body' );
		}
	}
}
