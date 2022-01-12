<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/controllerSample.php';

#
#
#

$route = \Nano\Route::getInstance();
$route->setRequestMethod( $_SERVER['REQUEST_METHOD'] ?? '' );
$route->setUrl( $_GET['url'] ?? '' );
$route->setViewPath( __DIR__ . '/views/' );
$route->setLayout( __DIR__ . '/views/layout' );

#
#
#

$route->get( 'people/dennis' )->action( '\controllerSample#helloDennis' )->view( 'viewSample' );
$route->get( 'people/gui' )->action( '\controllerSample#helloGui' )->view( 'viewSample2' );