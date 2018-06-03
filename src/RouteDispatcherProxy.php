<?php

namespace Rosem\Route;

use Psrnext\Route\RouteDispatcherInterface;

class RouteDispatcherProxy implements RouteDispatcherInterface
{
    private $routeCollector;

    private $variableDispatcher;

    private $placeholder;

    public function __construct(RouteCollector $routeCollector, DispatcherInterface $variableDispatcher, &$placeholder)
    {
        $this->routeCollector = $routeCollector;
        $this->variableDispatcher = $variableDispatcher;
        $this->placeholder = &$placeholder;
    }

    /**
     * Dispatches against the provided HTTP method verb and URI.
     *
     * @param string $method
     * @param string $uri
     *
     * @return array The handler and variables
     */
    public function dispatch(string $method, string $uri): array
    {
        $this->placeholder = new RouteDispatcher($this->routeCollector, $this->variableDispatcher);

        return $this->placeholder->dispatch($method, $uri);
    }
}
