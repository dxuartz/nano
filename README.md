# Nano

Micro biblioteca MVC

## Utilização

Para usar esta biblioteca basta usar o exemplo abaixo:
```PHP
<?php
use Nano\Dao;
require __DIR__ . '/vendor/autoload.php';
$person = Dao::find( 'Person', 1 );
```

## Requisitos

Necessário PHP 7.4 ou superior.