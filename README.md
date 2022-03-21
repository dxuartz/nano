# Nano

Micro biblioteca MVC

## Instalação

Para instalar esta dependência basta executar o comando abaixo
```shell
composer require dxuartz/nano
```

## Utilização

1º passo: criar um arquivo de configuração para acesso ao banco de dados, na raíz do seu projeto, com a seguinte estrutura:
Nome do arquivo: nano.conf.ini
```php
[database]
host = "127.0.0.1"  ; host do servidor MySQL
user = "root"       ; usuário com acesso ao banco
pass = "pass"       ; senha do usuário
name = "nano"       ; nome do banco de dados
port = "3306"       ; porta do MySQL no servidor
timezone = "-3:00"  ; timezone da sua área
```

2º passo: criar uma estrutura de diretórios na raíz do seu projeto, preferencialmente com uma pasta /public que será o document_root da aplicação e uma pasta /src no mesmo nível de forma que esta /src esteja abaixo do document_root da aplicação. A pasta /public terá pelo menos 2 arquivos descritos abaixo e a pasta /src terá o código fonte da sua aplicação.
```shell
/public
   .htaccess
   router.php
/src
   (...)
```

Arquivo .htaccess:
```shell
<IfModule mod_rewrite.c>
	SetEnvIf Authorization .+ HTTP_AUTHORIZATION=$0
	RewriteEngine On
	RewriteBase /
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteRule ^(.*)/$                           /$1 [R=301,L]
	RewriteRule ^/?$                              /router.php?url [NC,L,QSA]
	RewriteRule ^([A-Za-z0-9-_/]+)/?$             /router.php?url=$1 [NC,L,QSA]
	RewriteCond %{REQUEST_URI} .*\.(php|html)
	RewriteRule ^(.*)/                            / [R]
</IfModule>
```

Arquivo router.php:
```PHP
<?php
require __DIR__ . '/../vendor/autoload.php';
$route = \Nano\Route::getInstance();
$route->setRequestMethod( $_SERVER['REQUEST_METHOD'] ?? '' );
$route->setUrl( $_GET['url'] ?? '' );
$route->setViewPath( __DIR__ . '/../src/views/app/' );
$route->setLayout( __DIR__ . '/../src/views/layouts/app.php' );

$route->get( 'people' )->action( '\Controllers\People#list' )->view( 'people/list.php' );
$route->get( 'people/:person_id' )->action( '\Controllers\People#show' )->view( 'people/show.php' );
```

3º passo:
Exemplo de uso
```PHP
<?php
use Nano\Core\Dao;
require __DIR__ . '/vendor/autoload.php';
$person = Dao::find( 'Person', 1 );
```

## Requisitos

Necessário PHP 7.4 ou superior.