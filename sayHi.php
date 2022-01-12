<?php

class sayHi
{
	function to( $id )
	{
		return 'Hi ' . \Nano\Dao::find( 'Person', $id )->name;
	}
}