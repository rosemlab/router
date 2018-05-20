<?php

namespace Rosem\Route;

interface ChunkInterface
{
    public const REGEX = 0;

    public const ROUTES = 1;

    public function addRoute(RouteInterface $route): bool;
}
