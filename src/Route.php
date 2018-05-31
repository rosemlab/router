<?php

namespace Rosem\Route;

class Route implements RouteInterface
{
    protected $methods;

    protected $handler;

    protected $pathPattern;

    protected $hostPattern;

    protected $schemes;

    protected $middleware = [];

    protected $regex;

    protected $variableNames;

    public function __construct(array $methods, string $handler, ...$data)
    {
        $this->methods = $methods;
        $this->handler = $handler;
        [$this->pathPattern, $this->regex, $this->variableNames] = $data;
    }

    public function getRegex(): string
    {
        return $this->regex;
    }

    public function getVariableNames(): array
    {
        return $this->variableNames;
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function getHandler(): string
    {
        return $this->handler;
    }

    /**
     * Retrieves the path pattern of the route.
     * @return string
     */
    public function getPathPattern(): string
    {
        return $this->pathPattern;
    }

    /**
     * Retrieves the host pattern of the route.
     * @return string
     */
    public function getHostPattern(): string
    {
        return $this->hostPattern;
    }

    /**
     * Retrieves the scheme pattern of the route.
     * @return string
     */
    public function getSchemePattern(): string
    {
        return $this->schemes;
    }

    /**
     * Sets the middleware logic to be executed before route will be resolved.
     *
     * @param string ...$middleware
     *
     * @return void
     * @see \Psr\Http\Server\MiddlewareInterface
     */
    public function setMiddleware(string ...$middleware): void
    {
        $this->middleware = $middleware;
    }

    public function &getMiddlewareReference(): array
    {
        return $this->middleware;
    }
}
