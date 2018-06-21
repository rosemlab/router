<?php

namespace Rosem\Route;

interface RegexBasedDispatcherInterface
{
    public function dispatch(array &$routeExpressions, array &$routeData, string &$uri): array;
}
