<?php

require_once __DIR__ . '/vendor/autoload.php';

function detectCommonPrefix(string $prefix, string $anotherPrefix)
{
    $baseLength = strlen('');
    $commonLength = $baseLength;
    $end = min(strlen($prefix), strlen($anotherPrefix));

    for ($i = $baseLength; $i <= $end; ++$i) {
        if (substr($prefix, 0, $i) !== substr($anotherPrefix, 0, $i)) {
            break;
        }

        $commonLength = $i;
    }

    $commonPrefix = rtrim(substr($prefix, 0, $commonLength), '/');

    if (strlen($commonPrefix) > $baseLength) {
        return $commonPrefix;
    }

    return false;
}

var_dump(detectCommonPrefix('/admin/{user:[a-z]+}/(0)', '/admin/post/{id:\d+}/.*(1)'));
var_dump(detectCommonPrefix('/admin/([a-z]+)/(0)', '/admin/post/(\d+)/.*(1)'));

die;

$route = new Rosem\Route\RouteCollector();

$route->addRoute('GET', '/user/{name:\w+}', 'get_user');
$route->addRoute('GET', '/post/{id:\d+}', 'get_post');
$result = $route->make('GET', '/post/25');
var_dump($result);
