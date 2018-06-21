<?php

namespace Rosem\Route;

use Psrnext\Route\RouteDispatcherInterface;

class RouteDispatcher implements RouteDispatcherInterface
{
    use RouteMapTrait;
    use RouteRegexBasedDispatcherTrait;

    /**
     * RouteDispatcher constructor.
     *
     * @param array                         $staticRouteMap
     * @param array                         $variableRouteMap
     * @param RegexBasedDispatcherInterface $variableDispatcher
     */
    public function __construct(
        array $staticRouteMap,
        array $variableRouteMap,
        RegexBasedDispatcherInterface $variableDispatcher
    ) {
        $this->staticRouteMap = $staticRouteMap;
        $this->variableRouteMap = $variableRouteMap;
        $this->regexBasedDispatcher = $variableDispatcher;
    }
}
