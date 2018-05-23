<?php

namespace Rosem\Route;

interface ChunkInterface
{
    public const KEY_REGEX = 0;

    public const KEY_ROUTES = 1;

    public function addRoute(RouteInterface $route): bool;
}
