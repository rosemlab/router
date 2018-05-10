<?php

namespace Rosem\Route;

class RouteChunk
{
    protected $regex;
    protected $routesCount = 0;
    protected $routesLimit = 0;
    protected $regexLength = 0;
    protected $regexLimit = 0;

    protected function __construct(int $routesLimit = 33, ?int $regexLimit = null)
    {
        $this->routesLimit = $routesLimit;
        $this->regexLimit = $regexLimit ?: ini_get('pcre.backtrack_limit');
    }

    protected function getRegexBacktrackLimit(): int
    {
        return ini_get('pcre.backtrack_limit');
    }

    public function hasPlaceForRoute(int $routeLength): bool
    {
        return ($this->routesLimit === INF || $this->routesCount < $this->routesLimit) &&
            ($this->regexLength + $routeLength) <= $this->regexLimit;
    }

    public function addRoute(string $method, string $route)
    {

    }
}
