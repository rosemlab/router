<?php

namespace Rosem\Route\Dispatcher;

use Psrnext\Http\Message\ResponseStatus;
use Rosem\Route\Chunk\NumberBasedChunk;
use Rosem\Route\DispatcherInterface;

class NumberBasedDispatcher implements DispatcherInterface
{
    /**
     * @param array[] $chunkCollection
     * @param string  $uri
     *
     * @return array
     */
    public function dispatch(array $chunkCollection, string $uri): array
    {
        foreach ($chunkCollection as $routeChunk) {
            if (!preg_match(
                $routeChunk[NumberBasedChunk::KEY_REGEX],
                "$uri{$routeChunk[NumberBasedChunk::KEY_SUFFIX]}",
                $matches
            )) {
                continue;
            }

            unset($matches[key($matches)]);
            $indexStr = array_pop($matches);
            [$handler, $variableNames] =
                $routeChunk[NumberBasedChunk::KEY_ROUTES][
                    (int)($indexStr[0] . (isset($indexStr[1]) ? $indexStr[-1] : ''))
                ];

            return [ResponseStatus::FOUND, $handler, array_combine($variableNames, $matches)];
        }

        return [ResponseStatus::NOT_FOUND, ResponseStatus::PHRASES[ResponseStatus::NOT_FOUND]];
    }
}
