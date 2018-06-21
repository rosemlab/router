<?php

namespace Rosem\Route;

trait RouteMapTrait
{
    /**
     * @var array[]
     */
    protected $staticRouteMap;

    /**
     * @var RegexBasedDataGeneratorInterface[]
     */
    protected $variableRouteMap;
}
