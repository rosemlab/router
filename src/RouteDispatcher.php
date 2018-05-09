<?php

namespace Rosem\Route;

class RouteDispatcher
{
    /**
     * Friendly welcome
     *
     * @param string $httpMethod
     * @param string $uri
     *
     * @return array Returns the phrase passed in
     */
    public function dispatch(string $httpMethod, string $uri): array
    {
        return [$httpMethod, $uri];
    }
}
