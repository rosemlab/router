<?php

namespace Rosem\Route\Dispatcher;

use Rosem\Route\DataGenerator\GroupCountBasedRegexGenerator;
use function count;

class GroupCountBasedDispatcher extends AbstractRegexBasedDispatcher
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
        foreach ($routeData as &$data) {
            if (!preg_match($data[GroupCountBasedRegexGenerator::KEY_REGEX], $uri, $matches)) {
                continue;
            }

            [
                $handler,
                $middleware,
                $variableNames,
            ] = $routes[count($matches) + $data[GroupCountBasedRegexGenerator::KEY_OFFSET]];
            $variableData = [];

            /** @var string[] $variableNames */
            foreach ($variableNames as $index => &$variableName) {
                $variableData[$variableName] = &$matches[$index + 1];
            }

            return [self::ROUTE_FOUND, $handler, $middleware, $variableData];
        }

        return [self::ROUTE_NOT_FOUND];
    }
}
