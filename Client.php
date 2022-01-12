<?php

class Client
{
	public $id;
	public $name;
	public $created_at;
	public $updated_at;
	
	# ------------------------------------------ ------------------------------------------ #
	public final function sayHi()
	{
		return 'Hi ' . $this->name;
	}
}