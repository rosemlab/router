<?php

namespace Rosem\Route;

interface DataGeneratorInterface
{
    public function addRoute(RouteInterface $route): void;

    public function getExpressions(): array;

    public function getData(): array;
}
