<?php

namespace Rosem\Route;

use Psrnext\Route\RouteDispatcherInterface;
use Rosem\Route\Dispatcher\MarkBasedDispatcher;

class Router extends RouteCollector implements RouteDispatcherInterface
{
    protected $routeDispatcher;

    public function __construct()
    {
        parent::__construct();

        $this->routeDispatcher =
            new RouteDispatcherProxy($this, new MarkBasedDispatcher(), $this->routeDispatcher);
    }

    public function dispatch(string $method, string $uri): array
    {
        return $this->routeDispatcher->dispatch($method, $uri);
    }
}
