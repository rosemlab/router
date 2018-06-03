<?php

namespace Rosem\Route;

use Psrnext\Route\AbstractRouteCollector;
use Psrnext\Route\GenericRouteInterface;
use Psrnext\Route\RouteInterface;
use Rosem\Route\DataGenerator\GroupCountBasedDataGenerator;
use Rosem\Route\DataGenerator\MarkBasedDataGenerator;
use Rosem\Route\DataGenerator\StringNumberBasedDataGenerator;
use Rosem\Route\Dispatcher\AbstractDispatcher;
use Rosem\Route\Dispatcher\GroupCountBasedDispatcher;
use Rosem\Route\Dispatcher\MarkBasedDispatcher;
use Rosem\Route\Dispatcher\StringNumberBasedDispatcher;
use function count;

class RouteCollector extends AbstractRouteCollector
{
    public const STATIC_ROUTE_MAP = 0;

    public const VARIABLE_ROUTE_MAP = 1;

    /**
     * @var RouteCompiler
     */
    protected $compiler;

    protected $staticRouteMap = [];

    protected $variableRouteMap = [];

    protected $prefix = '';

    public function __construct()
    {
        $this->compiler = new RouteCompiler(new RouteParser());
//        $this->routeDispatcher = new GroupCountBasedDispatcher();
//        $this->routeDispatcher = new StringNumberBasedDispatcher();
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
            if (!isset($this->variableRouteMap[$method])) {
                $this->variableRouteMap[$method] = new MarkBasedDataGenerator();
//                new GroupCountBasedDataGenerator();
//                new StringNumberBasedDataGenerator();
            }

            if (count($route->getVariableNames())) { // dynamic route
                $this->variableRouteMap[$method]->addRoute($route);
            } else { // static route
                if (!isset($this->staticRouteMap[$method])) {
                    $this->staticRouteMap[$method] = [];
                }

                $this->staticRouteMap[$method][$routePattern] =
                    [$route->getMiddlewareReference(), $route->getHandler()];
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
     * @return array
     */
    public function getMap(): array
    {
        return [
            self::STATIC_ROUTE_MAP => $this->staticRouteMap,
            self::VARIABLE_ROUTE_MAP => $this->variableRouteMap,
        ];
    }
}
