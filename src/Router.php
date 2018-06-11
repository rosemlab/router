<?php

namespace Rosem\Route;

use Psrnext\Route\RouteDispatcherInterface;
use Rosem\Route\DataGenerator\MarkBasedDataGenerator;
use Rosem\Route\Dispatcher\MarkBasedDispatcher;

class Router extends RouteCollector implements RouteDispatcherInterface
{
    protected $routeDispatcher;

    /**
     * Router constructor.
     * @throws \InvalidArgumentException
     */
    public function __construct()
    {
        parent::__construct(new RouteCompiler(new RouteParser()), new MarkBasedDataGenerator());

        $this->routeDispatcher =
            new RouteDispatcherProxy($this, new MarkBasedDispatcher(), $this->routeDispatcher);
    }

    public function dispatch(string $method, string $uri): array
    {
        return $this->routeDispatcher->dispatch($method, $uri);
    }
}
