<?php

namespace Rosem\Route;

interface DispatcherInterface
{
    public function dispatch(array &$routeData, array &$routes, string &$uri): array;
}
