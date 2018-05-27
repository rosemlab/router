<?php

namespace Rosem\Route\Dispatcher;

use Rosem\Route\DataGenerator\GroupCountBasedDataGenerator;
use function count;

class GroupCountBasedDispatcher extends AbstractDispatcher
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
            if (!preg_match($data[GroupCountBasedDataGenerator::KEY_REGEX], $uri, $matches)) {
                continue;
            }

            [$handler, $variableNames] = $routes[count($matches) + $data[GroupCountBasedDataGenerator::KEY_OFFSET]];
            $variableData = [];

            /** @var string[] $variableNames */
            foreach ($variableNames as $index => &$variableName) {
                $variableData[$variableName] = &$matches[$index + 1];
            }

            return [self::ROUTE_FOUND, $handler, $variableData];
        }

        return [self::ROUTE_NOT_FOUND, self::ROUTE_NOT_FOUND_PHRASE];
    }
}
