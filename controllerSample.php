<?php
require __DIR__ . '/Person.php';

class controllerSample
{
	# ------------------------------------------ ------------------------------------------ #
	public final function helloDennis()
	{
		$person = \Nano\Dao::find( 'Person', 2 );
		return ( object ) [ 'person' => $person ];
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final function helloGui()
	{
		$person = \Nano\Dao::find( 'Person', 2 );
		return ( object ) [ 'person' => $person, 'layout' => __DIR__ . '/views/layout2' ];
	}
}
