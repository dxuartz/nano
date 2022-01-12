<?php
use \Nano\Dao;
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/sayHi.php';
require __DIR__ . '/Client.php';
require __DIR__ . '/Person.php';

#
#
#

try
{
	$person1 = Dao::find( 'Person', 1 );
}
catch ( \Exception $e )
{
	echo $e->getMessage();
}

$person2 = Dao::find( 'Person', 2 );
$person3 = Dao::find( 'Person', 3 );

#
#
#

var_dump( $person1 );
echo '<hr>';
echo $person1->sayHi();
echo '<hr>';
echo $person2->sayHi();
echo '<hr>';
echo $person3->sayHi();
echo '<hr>';

#
#
#

$people = Dao::findAll( 'Person', "`name` LIKE '%a%'" );

foreach ( $people as $person )
{
	echo $person->id . ' ';
	echo $person->sayHi();
	echo '<hr>';
}

#
#
#

echo 'count person name like dennis: ' . Dao::count( 'Person', "`name` LIKE '%dennis%'" );
echo '<hr>';
echo 'count person: ' . Dao::count( 'Person' );
echo '<hr>';

#
#
#

echo 'findParent: ';
echo Dao::findParent( 'Client', $person1 )->name;
echo '<hr>';

#
#
#

var_dump( Dao::query( "SELECT p.name AS person_name, c.name AS client_name FROM people p JOIN clients c ON ( p.client_id = c.id ) WHERE p.name LIKE '%t%'" ) );
echo '<hr>';

#
#
#

echo 'update updated_at: ';
var_dump( Dao::execute( "UPDATE people SET updated_at = NOW() WHERE id = 1" ) );
echo '<hr>';

#
#
#

$person13 = Dao::find( 'Person', 13 );
echo 'delete person13: ';

try
{
	Dao::delete( $person13 );
	echo 'ok';
}
catch ( \Nano\DaoException $e )
{
	echo $e->getMessage();
}

echo '<hr>';

#
#
#

echo 'update person: ';
$person1->name = 'Dennis C Schwartz';
var_dump( Dao::save( $person1 ) );
echo '<hr>';