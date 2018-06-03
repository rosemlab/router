<?php

namespace Rosem\Route;

use Psrnext\Route\RouteDispatcherInterface;

class RouteDispatcher implements RouteDispatcherInterface
{
    protected $routeCollector;

    protected $staticRouteMap;

    protected $variableRouteExpressions = [];

    protected $variableRouteData = [];

    /**
     * @var DispatcherInterface
     */
    protected $variableDispatcher;

    public function __construct(RouteCollector $routeCollector, DispatcherInterface $variableDispatcher)
    {
        $this->routeCollector = $routeCollector;
        $this->variableDispatcher = $variableDispatcher;
        [
            RouteCollector::STATIC_ROUTE_MAP => $this->staticRouteMap,
            RouteCollector::VARIABLE_ROUTE_MAP => $variableRouteMap,
        ] = $this->routeCollector->getMap();

        /** @var DataGeneratorInterface[] $variableRouteMap */
        foreach ($variableRouteMap as $method => $dataGenerator) {
            $this->variableRouteExpressions[$method] = $variableRouteMap[$method]->getExpressions();
            $this->variableRouteData[$method] = $variableRouteMap[$method]->getData();
        }
    }

    /**
     * Dispatches against the provided HTTP method verb and URI.
     * Returns array with one of the following formats:
     *     [
     *         StatusCodeInterface::STATUS_NOT_FOUND
     *     ]
     *     [
     *         StatusCodeInterface::STATUS_METHOD_NOT_ALLOWED,
     *         [RequestMethodInterface::METHOD_GET, other allowed methods...]
     *     ]
     *     [
     *         StatusCodeInterface::STATUS_FOUND,
     *         $handler,
     *         [$middleware1, $middleware2, ...],
     *         ['varName' => 'value', other variables...]
     *     ]
     * @see \Fig\Http\Message\RequestMethodInterface
     * @see \Fig\Http\Message\StatusCodeInterface
     *
     * @param string $method
     * @param string $uri
     *
     * @return array The handler and variables
     */
    public function dispatch(string $method, string $uri): array
    {
        if (isset($this->staticRouteMap[$method][$uri])) {
            [$middleware, $handler] = $this->staticRouteMap[$method][$uri];

            return [200, $middleware, $handler, []]; // TODO: move to static dispatcher
        }

        return $this->variableDispatcher->dispatch(
            $this->variableRouteExpressions[$method],
            $this->variableRouteData[$method],
            $uri
        );
    }
}
