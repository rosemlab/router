<?php

require_once __DIR__ . '/vendor/autoload.php';

$route = new Rosem\Route\RouteCollector();

$route->addRoute('GET', '/user/{name:\w+}', 'get_user');
$route->addRoute('GET', '/post/{id:\d+}', 'get_post');
$result = $route->make('GET', '/post/25');
var_dump($result);
