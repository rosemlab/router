<?php

namespace Rosem\Route;

use Psrnext\Route\RouteDispatcherInterface;
use Rosem\Route\DataGenerator\MarkBasedDataGenerator;
use Rosem\Route\Dispatcher\MarkBasedDispatcher;

class Router extends RouteCollector implements RouteDispatcherInterface
{
    use RouteRegexBasedDispatcherTrait;

    /**
     * Router constructor.
     *
     * @param int $routeCountPerRegex
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(int $routeCountPerRegex = PHP_INT_MAX)
    {
        parent::__construct(new RouteCompiler(new RouteParser()), new MarkBasedDataGenerator($routeCountPerRegex));

        $this->regexBasedDispatcher = new MarkBasedDispatcher();
    }

    public function dispatch(string $method, string $uri): array
    {
        if (isset($this->staticRouteMap[$method][$uri])) {
            [$handler, $middleware] = $this->staticRouteMap[$method][$uri];

            return [200, &$handler, &$middleware, []];
        }

        return $this->regexBasedDispatcher->dispatch(
            $this->variableRouteMap[$method]->routeExpressions,
            $this->variableRouteMap[$method]->routeData,
            $uri
        );
    }
}
