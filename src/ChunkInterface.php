<?php

namespace Rosem\Route;

interface ChunkInterface
{
    public function addRoute(RouteInterface $route): bool;

    public function getRegex(): string;
}
