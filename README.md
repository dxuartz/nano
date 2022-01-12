# Nano

Micro biblioteca MVC

## Instalação

Para instalar esta dependência basta executar o comando abaixo
```shell
composer require dxuartz/nano
```

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