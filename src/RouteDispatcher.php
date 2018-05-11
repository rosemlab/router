<?php

namespace Rosem\Route;

class RouteDispatcher
{
    protected $suffix = '/';

    public function __construct(int $routesMaxLimit = 99)
    {
        $this->suffix .= '0123456789876543210'; //TODO: auto-generate
    }

    public function dispatch(array $routeChunkCollection, string $uri): array
    {
        $uri .= $this->suffix;

        foreach ($routeChunkCollection as $routeChunk) {
            if (!preg_match('~^(?|' . $routeChunk->regex . ')\d*$~', $uri, $matches)) {
                continue;
            }

            $indexStr = array_pop($matches);
//        $indexStr = end($this->matches);
            array_shift($matches);

            return [
                $routeChunk[(int) ($indexStr[0] . $indexStr[-1])],
                &$matches,
            ];
        }

        return [function ($errorCode) {
            return "$errorCode Not found";
        }, [404]];
    }
}
