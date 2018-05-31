<?php

namespace Rosem\Route;

use Psrnext\Route\AbstractRouteCollector;
use Psrnext\Route\GenericRouteInterface;
use Psrnext\Route\RouteDispatcherInterface;
use Psrnext\Route\RouteInterface;
use Rosem\Route\DataGenerator\GroupCountBasedDataGenerator;
use Rosem\Route\DataGenerator\MarkBasedDataGenerator;
use Rosem\Route\DataGenerator\StringNumberBasedDataGenerator;
use Rosem\Route\Dispatcher\AbstractDispatcher;
use Rosem\Route\Dispatcher\GroupCountBasedDispatcher;
use Rosem\Route\Dispatcher\MarkBasedDispatcher;
use Rosem\Route\Dispatcher\StringNumberBasedDispatcher;
use function count;

class Router extends AbstractRouteCollector implements RouteDispatcherInterface
{
    /**
     * @var RouteCompiler
     */
    protected $compiler;

    protected $routeDispatcher;

    protected $staticRouteMap = [];

    protected $variableRouteMap = [];

    protected $variableRouteData = [];

    protected $prefix = '';

    /**
     * @var DataGeneratorInterface
     */
    protected $lastChunk;

    public function __construct()
    {
        $this->compiler = new RouteCompiler(new RouteParser());
//        $this->routeDispatcher = new GroupCountBasedDispatcher();
//        $this->routeDispatcher = new StringNumberBasedDispatcher();
        $this->routeDispatcher = new MarkBasedDispatcher();
    }

    protected static function normalize(string $route): string
    {
        return '/' . trim($route, '/');
    }

    /**
     * @param string|string[] $methods
     * @param string          $routePattern
     * @param mixed           $handler
     *
     * @return RouteInterface
     * @throws \Exception
     */
    public function addRoute($methods, string $routePattern, $handler): RouteInterface
    {
        $route = $this->compiler->compile((array)$methods, self::normalize($routePattern), $handler);

        foreach ($route->getMethods() as $method) {
            if (!isset($this->variableRouteData[$method])) {
                $this->variableRouteMap[$method] = [];
                $this->variableRouteData[$method] = [];
//                $this->lastChunk = new GroupCountBasedDataGenerator(
//                    $this->routeData[$method],
//                    $this->routes[$method]
//                );
//                $this->lastChunk = new StringNumberBasedDataGenerator(
//                    $this->routeData[$method],
//                    $this->routes[$method]
//                );
                $this->lastChunk = new MarkBasedDataGenerator(
                    $this->variableRouteMap[$method],
                    $this->variableRouteData[$method]
                );
            }

            if (count($route->getVariableNames())) { // dynamic route
                $this->lastChunk->addRoute($route);
            } else { // static route
                if (!isset($this->staticRouteMap[$method])) {
                    $this->staticRouteMap[$method] = [];
                }

                $this->staticRouteMap[$method][$routePattern] = $handler;
            }
        }

        return $route;
    }

    /**
     * @param string   $prefix
     * @param callable $group
     *
     * @return GenericRouteInterface
     */
    public function addGroup(string $prefix, callable $group): GenericRouteInterface
    {
        $this->prefix .= self::normalize($prefix);
        $group($this);
        $this->prefix = '';
        // TODO: return
    }

    /**
     * @param string $method
     * @param string $uri
     *
     * @return array
     */
    public function dispatch($method, string $uri): array
    {
        if (isset($this->staticRouteMap[$method][$uri])) {
            $handler = $this->staticRouteMap[$method][$uri];

            return [200, $handler, []]; // TODO: move to static dispatcher
        }

        return $this->routeDispatcher->dispatch(
            $this->variableRouteMap[$method],
            $this->variableRouteData[$method],
            $uri
        );
    }
}
