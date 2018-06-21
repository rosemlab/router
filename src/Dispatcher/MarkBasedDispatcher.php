<?php

namespace Rosem\Route\Dispatcher;

class MarkBasedDispatcher extends AbstractRegexBasedDispatcher
{
    /**
     * @param array  $routeExpressions
     * @param array  $routeData
     * @param string $uri
     *
     * @return array
     */
    public function dispatch(array &$routeExpressions, array &$routeData, string &$uri): array
    {
        foreach ($routeExpressions as &$regex) {
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
