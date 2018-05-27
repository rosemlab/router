<?php

namespace Rosem\Route\Dispatcher;

use Rosem\Route\DataGenerator\StringNumberBasedDataGenerator;

class StringNumberBasedDispatcher extends AbstractDispatcher
{
    /**
     * @param array[] $routeData
     * @param array   $routes
     * @param string  $uri
     *
     * @return array
     */
    public function dispatch(array &$routeData, array &$routes, string &$uri): array
    {
        foreach ($routeData as &$data) {
            if (!preg_match(
                $data[StringNumberBasedDataGenerator::KEY_REGEX],
                $uri . $data[StringNumberBasedDataGenerator::KEY_SUFFIX],
                $matches
            )) {
                continue;
            }

            unset($matches[0]);
            $segmentCount = $data[StringNumberBasedDataGenerator::KEY_SEGMENT_COUNT];
            $indexString = '';

            do {
                $lastMatch = array_pop($matches);
                $indexString = $lastMatch[0] . $indexString . (isset($lastMatch[1]) ? $lastMatch[-1] : '');
            } while (--$segmentCount);

            [$handler, $variableNames] = $routes[(int)$indexString];

            return [self::ROUTE_FOUND, $handler, array_combine($variableNames, $matches)];
        }

        return [self::ROUTE_NOT_FOUND, self::ROUTE_NOT_FOUND_PHRASE];
    }
}
