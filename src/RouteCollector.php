<?php

namespace Rosem\Route;

use Psrnext\Http\Message\RequestMethod;

class RouteCollector
{
    protected $prefix = '';

    public function __construct()
    {
        $backtrackLimit = ini_get('pcre.backtrack_limit');
        $tail = '/' . str_repeat(' ', RouteCollection::ROUTES_CHUNK_LIMIT);
        $this->routeCollection = new RouteCollection($backtrackLimit, $tail);
    }

    static function normalize(string $route): string
    {
        return '/' . trim($route, '/');
    }

    public function prefixy(string $route): string
    {
        // check route is relative (without "/") or absolute (with "/")
        return $route[0] === '/' ? static::normalize($route) : $this->prefix . static::normalize($route);
    }

    /**
     * @param string                $requestMethod
     * @param string                $route
     * @param string|array|\Closure $handler
     */
    public function addRoute(string $requestMethod, string $route, $handler): void
    {
        $this->routeCollection->add($route, $handler);
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
    public function make($method, string $route): array
    {
        return $this->routeCollection->get($route);
    }

    /**
     * @param string|array          $methods
     * @param string                $route
     * @param string|array|\Closure $handler
     */
    public function match($methods, string $route, $handler)
    {
        foreach ((array)$methods as $method) {
            $this->addRoute($method, $route, $handler);
        }
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
