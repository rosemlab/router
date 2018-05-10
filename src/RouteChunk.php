<?php

namespace Rosem\Route;

class RouteChunk
{
    protected $regex;
    protected $routes = [];
    protected $routesLimit = 0;
    protected $regexLength = 0;
    protected $regexLimit = 0;
    protected $matches = [];

    protected static $suffix = '/0123456789';

    public function __construct(int $routesLimit = 66, ?int $regexLimit = null)
    {
        $this->routesLimit = $routesLimit;
        $this->regexLimit = $regexLimit ?: ini_get('pcre.backtrack_limit') ?: 1000000;
    }

    public function hasPlaceForRoute(int $routeLength): bool
    {
        return ($this->routesLimit === INF || \count($this->routes) < $this->routesLimit) &&
            ($this->regexLength + $routeLength) <= $this->regexLimit;
    }

    public function addRoute(RouteInterface $route): void
    {
        $rest = \count($this->routes);
        $regex = '(?:' . $route->getRegex() . ')/(' . ($rest ? '.*' : '') .
            ($rest < 10 ? $rest : (intdiv($rest, 10) . '.*' . $rest % 10))
            . ')';
        $this->regex = $rest ? "$regex|$this->regex" : $regex;
        $this->routes[] = $route;
    }

    public function matchRoute(string $uri): bool
    {
        return preg_match("~^(?|{$this->regex})\d*$~", $uri . static::$suffix, $this->matches);
    }

    public function getMatchedRoute(): array
    {
        $indexStr = array_pop($this->matches);
//        $indexStr = end($this->matches);
        array_shift($this->matches);

        return [
            $this->routes[(int) ($indexStr[0] . $indexStr[-1])],
            &$this->matches,
        ];
    }
}
