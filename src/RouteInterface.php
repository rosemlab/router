<?php

namespace Rosem\Route;

use Psrnext\Route\RouteInterface as StandardRouteInterface;

interface RouteInterface extends StandardRouteInterface
{
    public function getRegex(): string;

    public function getVariableNames(): array;

    public function &getMiddlewareReference(): array;
}
