<?php

namespace Rosem\Route;

use Psrnext\Route\AbstractRouteCollector;
use Psrnext\Route\RouteGroupInterface;
use Psrnext\Route\RouteInterface;
use function count;

class RouteCollector extends AbstractRouteCollector
{
    public const STATIC_ROUTE_MAP = 0;

    public const VARIABLE_ROUTE_MAP = 1;

    /**
     * @var RouteCompiler
     */
    protected $compiler;

    /**
     * @var DataGeneratorInterface
     */
    protected $dataGenerator;

    protected $staticRouteMap = [];

    protected $variableRouteMap = [];

    protected $prefix = '';

    public function __construct(RouteCompilerInterface $compiler, DataGeneratorInterface $dataGenerator)
    {
        $this->compiler = $compiler;
        $this->dataGenerator = $dataGenerator;
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
            if (count($route->getVariableNames())) { // dynamic route
                if (!isset($this->variableRouteMap[$method])) {
                    $this->variableRouteMap[$method] = clone $this->dataGenerator;
                }

                $this->variableRouteMap[$method]->addRoute($route);
            } else { // static route
                if (!isset($this->staticRouteMap[$method])) {
                    $this->staticRouteMap[$method] = [];
                }

                $middleware = &$route->getMiddlewareListReference();
                $this->staticRouteMap[$method][$routePattern] = [$route->getHandler(), &$middleware];
            }
        }

        return $route;
    }

    /**
     * @param string   $prefix
     * @param callable $group
     *
     * @return RouteGroupInterface
     */
    public function addGroup(string $prefix, callable $group): RouteGroupInterface
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
