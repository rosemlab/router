<?php

namespace Rosem\Route;

interface DispatcherInterface
{
    public function dispatch(array &$routeMap, array &$routeData, string &$uri): array;
}
