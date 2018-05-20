<?php

namespace Rosem\Route\Dispatcher;

class NumberBasedDispatcher
{
    protected $suffix = '/';

    public function __construct(int $routesMaxLimit = 99)
    {
        $this->suffix .= str_pad(
            '',
            10 * (int)floor(log10($routesMaxLimit) + 1), // get count of numbers
            '0123456789'
        );
    }

    public function dispatch(array $routeChunkCollection, string $uri): array
    {
        $uri .= $this->suffix;

        foreach ($routeChunkCollection as $routeChunk) {
            if (!preg_match($routeChunk->getRegex(), $uri, $matches)) {
                continue;
            }

            $indexStr = array_pop($matches);
//        $indexStr = end($matches);
            array_shift($matches);

            return [
                $routeChunk[(int)($indexStr[0] . $indexStr[-1])],
                &$matches,
            ];
        }

        return [
            function ($errorCode) {
                return "$errorCode Not found";
            },
            [404],
        ];
    }
}
