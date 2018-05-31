<?php

namespace Rosem\Route\Dispatcher;

class MarkBasedDispatcher extends AbstractDispatcher
{
    /**
     * @param array  $routeMap
     * @param array  $routeData
     * @param string $uri
     *
     * @return array
     */
    public function dispatch(array &$routeMap, array &$routeData, string &$uri): array
    {
        foreach ($routeMap as &$regex) {
            if (preg_match($regex, $uri, $matches)) {
                [$handler, $middleware, $variableNames] = $routeData[$matches['MARK']];
                $variableData = [];

                /** @var string[] $variableNames */
                foreach ($variableNames as $index => &$variableName) {
                    $variableData[$variableName] = &$matches[$index + 1];
                }

                return [self::ROUTE_FOUND, $handler, $middleware, $variableData];
            }
        }

        return [self::ROUTE_NOT_FOUND];
    }
}
