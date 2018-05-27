<?php

namespace Rosem\Route\Dispatcher;

class MarkBasedDispatcher extends AbstractDispatcher
{
    /**
     * @param array  $routeData
     * @param array  $routes
     * @param string $uri
     *
     * @return array
     */
    public function dispatch(array &$routeData, array &$routes, string &$uri): array
    {
        foreach ($routeData as &$regex) {
            if (preg_match($regex, $uri, $matches)) {
                [$handler, $variableNames] = $routes[$matches['MARK']];
                $variableData = [];

                /** @var string[] $variableNames */
                foreach ($variableNames as $index => &$variableName) {
                    $variableData[$variableName] = &$matches[$index + 1];
                }

                return [self::ROUTE_FOUND, $handler, $variableData];
            }
        }

        return [self::ROUTE_NOT_FOUND, self::ROUTE_NOT_FOUND_PHRASE];
    }
}
