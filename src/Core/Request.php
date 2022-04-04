<?php
namespace Nano\Core;
use stdClass;

class Request
{
	public readonly stdClass $query;
	public readonly stdClass $params;
	public readonly stdClass $body;
	
	# ------------------------------------------ ------------------------------------------ #
	public function __construct()
	{
		$this->query = new stdClass();
		$this->params = new stdClass();
		$this->body = new stdClass();
		$this->populate();
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function add( string $key, mixed $value, string $type ) : bool
	{
		if ( ! in_array( $type, [ 'query', 'params', 'body' ] ) )
		{
			return false;
		}
		
		$this->$type->$key = $value;
		return true;
	}
	
	# ------------------------------------------ ------------------------------------------ #
	private function populate() : void
	{
		$this->populateQuery();
		$this->populateBodyFromFormData();
		$this->populateBodyFromRawData();
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
	private function populateBodyFromRawData() : bool
	{
		$input = file_get_contents( 'php://input' );
		
		if ( ! $input )
		{
			return false;
		}
		
		$json_body = json_decode( $input, true );
		
		if ( ! json_last_error() )
		{
			$this->add( 'json', $json_body, 'body' );
			return true;
		}
		
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
			
			$this->add( $matches[1], $matches[2], 'body' );
		}
		
		return true;
	}
}
