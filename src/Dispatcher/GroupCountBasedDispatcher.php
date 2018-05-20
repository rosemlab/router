<?php

namespace Rosem\Route\Dispatcher;

use Psrnext\Http\Message\ResponseStatus;
use Rosem\Route\ChunkInterface;
use Rosem\Route\DispatcherInterface;
use function count;

class GroupCountBasedDispatcher implements DispatcherInterface
{
    /**
     * @param ChunkInterface[] $chunkCollection
     * @param string           $uri
     *
     * @return array
     */
    public function dispatch(array $chunkCollection, string $uri): array
    {
        foreach ($chunkCollection as $routeChunk) {
            if (!preg_match($routeChunk[ChunkInterface::REGEX], $uri, $matches)) {
                continue;
            }

            [$handler, $variableNames] = $routeChunk[ChunkInterface::ROUTES][count($matches)];
            $variableData = [];
            $i = 0;

            /** @var string[] $variableNames */
            foreach ($variableNames as $variableName) {
                $variableData[$variableName] = $matches[++$i];
            }

            return [ResponseStatus::OK, $handler, $variableData];
        }

        return [ResponseStatus::NOT_FOUND, ResponseStatus::PHRASES[ResponseStatus::NOT_FOUND]];
    }
}
