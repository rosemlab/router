<?php

namespace Rosem\Route;

interface DataGeneratorInterface
{
    public function addRoute(RouteInterface $route): void;
}
