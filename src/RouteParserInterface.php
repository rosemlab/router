<?php

namespace Rosem\Route;

interface RouteParserInterface
{
    public function parse(string $routePattern): array;
}
