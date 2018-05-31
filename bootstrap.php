<?php

require_once __DIR__ . '/vendor/autoload.php';

$route = new Rosem\Route\Router();
//$route->addRoute('GET', '/user/{fullName:(?<firstName>[a-z]+\w*)_(?<lastName>[a-z]\w*)}', 'test');
$route->addRoute('GET', '/a/{arg1}/{arg2}', '2')->setMiddleware('MIDDLEWARE');
$route->addRoute('GET', '/a/{arg1}/{arg2}/{arg3}/{arg4}', '4');
$route->addRoute('GET', '/a/{arg1}/{arg2}/{arg3}', '3');
$route->addRoute('GET', '/a/{arg1}/{arg2}/{arg3}/{arg4}/{arg5}/{arg6}/{arg7}/{arg8}/{arg9}/{arg10}/{arg11}/{arg12}/{arg13}/{arg14}', '14');
$res = $route->dispatch('GET', '/a/1/2');
$res = $route->dispatch('GET', '/a/1/2/3/4');
$res = $route->dispatch('GET', '/a/1/2/3');
$res = $route->dispatch('GET', '/a/1/2/3/4/5/6/7/8/9/10/11/12/13/14');
var_dump($route->dispatch('GET', '/user/roman_shevchenko'));

die;

$tree = new \Rosem\Route\RegexTreeNode();
$tree->addRegex('/(en|fr)/admin/post/?');
$tree->addRegex('/(en|fr)/admin/post/new');
$tree->addRegex('/(en|fr)/admin/post/(\d+)');
$tree->addRegex('/(en|fr)/admin/post/(\d+)/edit');
$tree->addRegex('/(en|fr)/admin/post/(\d+)/delete');
$tree->addRegex('/(en|fr)/blog/?');
$tree->addRegex('/(en|fr)/blog/rss\.xml');
$tree->addRegex('/(en|fr)/blog/page/([^/]++)');
$tree->addRegex('/(en|fr)/blog/posts/([^/]++)');
$tree->addRegex('/(en|fr)/blog/comment/(\d+)/new');
$tree->addRegex('/(en|fr)/blog/search');
$tree->addRegex('/(en|fr)/login');
$tree->addRegex('/(en|fr)/logout');
$tree->addRegex('/(en|fr)?');
var_dump($tree->getRegex());

die;

//function detectCommonPrefix(string $prefix, string $anotherPrefix)
//{
//    $baseLength = strlen('');
//    $commonLength = $baseLength;
//    $end = min(strlen($prefix), strlen($anotherPrefix));
//
//    for ($i = $baseLength; $i <= $end; ++$i) {
//        if (substr($prefix, 0, $i) !== substr($anotherPrefix, 0, $i)) {
//            break;
//        }
//
//        $commonLength = $i;
//    }
//
//    $commonPrefix = rtrim(substr($prefix, 0, $commonLength), '/');
//
//    if (strlen($commonPrefix) > $baseLength) {
//        return $commonPrefix;
//    }
//
//    return false;
//}
//
//var_dump(detectCommonPrefix('/admin/{user:[a-z]+}/(0)', '/admin/post/{id:\d+}/.*(1)'));
//var_dump(detectCommonPrefix('/admin/([a-z]+)/(0)', '/admin/post/(\d+)/.*(1)'));
//
//die;

$route = new Rosem\Route\Router();

$route->addRoute('GET', '/user/{test}/{id}/{name:\w+}', 'get_user');
$route->addRoute('GET', '/post/{id:\d+}', 'get_post');
$result = $route->dispatch('GET', '/post/25');
var_dump($result);
