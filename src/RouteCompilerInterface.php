<?php

namespace Rosem\Route;

interface RouteCompilerInterface
{
    public function compile(array $methods, string $routePattern, string $handler): RouteInterface;
}
