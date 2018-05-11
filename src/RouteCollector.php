<?php

namespace Rosem\Route;

use Psrnext\Http\Message\RequestMethod;

class RouteCollector
{
    protected $routeParser;
    protected $routeDispatcher;

    public function __construct()
    {
        $this->routeParser = new RouteParser();
        $this->routeDispatcher = new RouteDispatcher();
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
     * @param string|array $methods
     * @param string       $route
     * @param              $handler
     */
    public function addRoute($methods, string $route, $handler)
    {
        foreach ((array) $methods as $method) {
            $route = new Route($method, $this->routeParser->parse($route));

            if (!isset($this->routes[$method])) {
                $this->routes[$method] = [new RouteChunk()];
            }

            $lastChunk = end($this->routes[$method]);

            if (!$lastChunk->addRoute($route)) {
                $this->routes[$method][] = $lastChunk = new RouteChunk();

                if (!$lastChunk->addRoute($route)) {
                    throw new \Exception('Your route is too long');
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
