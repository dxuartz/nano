<?php

class Person
{
	public $id;
	public $client_id;
	public $name;
	public $email;
	public $created_at;
	public $updated_at;
	
	# ------------------------------------------ ------------------------------------------ #
	public final function sayHi()
	{
		return 'Hi ' . $this->name;
	}
}