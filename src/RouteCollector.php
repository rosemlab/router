<?php

namespace Rosem\Route;

use InvalidArgumentException;
use Psrnext\Http\Message\RequestMethod;
use Rosem\Route\Chunk\GroupCountBasedChunk;
use Rosem\Route\Chunk\NumberBasedChunk;
use Rosem\Route\Dispatcher\GroupCountBasedDispatcher;
use Rosem\Route\Dispatcher\NumberBasedDispatcher;
use function count;

class RouteCollector
{
    protected $routeParser;

    protected $routeDispatcher;

    protected $routes = [];

    protected $prefix = '';

    protected $lastChunk;

    public function __construct()
    {
        $this->routeParser = new RouteParser();
//        $this->routeDispatcher = new GroupCountBasedDispatcher();
        $this->routeDispatcher = new NumberBasedDispatcher();
    }

    public static function normalize(string $route): string
    {
        return '/' . trim($route, '/');
    }

    public function prefixy(string $route): string
    {
        // check route is relative (without "/") or absolute (with "/")
        return $route[0] === '/' ? static::normalize($route) : $this->prefix . static::normalize($route);
    }

    /**
     * @param string|string[]          $methods
     * @param string                   $route
     * @param string|string[]|callable $handler
     *
     * @throws \Exception
     */
    public function addRoute($methods, string $route, $handler): void
    {
        foreach ((array)$methods as $method) {
            foreach ($this->routeParser->parse($route) as $routeData) {
                $routeInstance = new Route($method, $handler, ...$routeData);

                if (!isset($this->routes[$method])) {
                    $this->routes[$method] = [[]];
//                    $this->lastChunk = new GroupCountBasedChunk($this->routes[$method][0]);
                    $this->lastChunk = new NumberBasedChunk($this->routes[$method][0]);
                }

                if (count($routeInstance->getVariableNames())) { // Dynamic route
                    if (!$this->lastChunk->addRoute($routeInstance)) {
                        $lastChunk = [];
                        $this->routes[$method][] = &$lastChunk;
//                        $this->lastChunk = new GroupCountBasedChunk($lastChunk);
                        $this->lastChunk = new NumberBasedChunk($lastChunk);
                        $this->lastChunk->addRoute($routeInstance);
                    }
                } else { // Static route
                    // TODO: static route handling
                }
            }
        }
    }

    /**
     * @param string|\Closure       $prefix
     * @param string|array|\Closure $group
     */
    public function prefix(string $prefix, $group)
    {
        $this->prefix = ($prefix[0] === '/'
            ? static::normalize($prefix)
            : $this->prefix . static::normalize($prefix));
        is_callable($group) ? $group() : call_user_func($group);
        $this->prefix = '';
    }

    /**
     * @param string $method
     * @param string $route
     *
     * @return array
     */
    public function make($method, string $uri): array
    {
        return $this->routeDispatcher->dispatch($this->routes[$method], $uri);
    }

    /**
     * @param string                $route
     * @param string|array|\Closure $handler
     */
    public function get(string $route, $handler)
    {
        $this->addRoute(RequestMethod::GET, $this->prefixy($route), $handler);
    }

    /**
     * @param string                $route
     * @param string|array|\Closure $handler
     */
    public function post(string $route, $handler)
    {
        $this->addRoute(RequestMethod::POST, $this->prefixy($route), $handler);
    }

    /**
     * @param string                $route
     * @param string|array|\Closure $handler
     */
    public function put(string $route, $handler)
    {
        $this->addRoute(RequestMethod::PUT, $this->prefixy($route), $handler);
    }

    /**
     * @param string                $route
     * @param string|array|\Closure $handler
     */
    public function patch(string $route, $handler)
    {
        $this->addRoute(RequestMethod::PATCH, $this->prefixy($route), $handler);
    }

    /**
     * @param string                $route
     * @param string|array|\Closure $handler
     */
    public function delete(string $route, $handler)
    {
        $this->addRoute(RequestMethod::DELETE, $this->prefixy($route), $handler);
    }

    /**
     * @param string                $route
     * @param string|array|\Closure $handler
     */
    public function options(string $route, $handler)
    {
        $this->addRoute(RequestMethod::OPTIONS, $this->prefixy($route), $handler);
    }
}
