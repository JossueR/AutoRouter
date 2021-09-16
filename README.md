#AutoRouter
AutoRouter is a small lib for routing in PHP object-oriented projects

#Prerequisitos
* PHP 5.6 or greater
* URL Rewriting

#Installation
``` 
composer require jossuer/auto_router
```

#Apache URL Rewriting
.htaccess

``` 
Options +FollowSymLinks
IndexIgnore */*
RewriteEngine on

# if a directory or a file exists, use it directly
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

# otherwise forward it to index.php
RewriteRule . index.php
```

#Usage
index.php
```php
<?php
require_once 'vendor/autoload.php';

$root_path = "";
$default_controller = "Home";
$default_method = "index";
$namespace = "\\App\\Controllers\\";
$controllerSuffix = "Controller";

use AutoRouter\Router;

$router = new Router();
$router->setRootPath($root_path)
    ->setDefaultController($default_controller)
    ->setDefaultMethod($default_method)
    ->setNamespace($namespace)
    ->setControllerSuffix($controllerSuffix);
$router->exec();
```