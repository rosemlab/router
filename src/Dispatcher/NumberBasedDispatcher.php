<?php

namespace Rosem\Route\Dispatcher;

use Psrnext\Http\Message\ResponseStatus;
use Rosem\Route\Chunk\NumberBasedChunk;
use Rosem\Route\DispatcherInterface;
use function strlen;

class NumberBasedDispatcher implements DispatcherInterface
{
    protected $suffix = '/';

    public function __construct(int $routeMaxCount = 99)
    {
        $this->suffix .= str_pad('', 10 * strlen((string)$routeMaxCount), '0123456789') . '/';
    }

    /**
     * @param array[] $chunkCollection
     * @param string  $uri
     *
     * @return array
     */
    public function dispatch(array $chunkCollection, string $uri): array
    {
        $uri .= $this->suffix;

        foreach ($chunkCollection as $routeChunk) {
            if (!preg_match($routeChunk[NumberBasedChunk::REGEX], $uri, $matches)) {
                continue;
            }

            unset($matches[key($matches)]);
            $indexStr = array_pop($matches);
            [$handler, $variableNames] =
                $routeChunk[NumberBasedChunk::ROUTES][(int)($indexStr[0] . (isset($indexStr[1]) ? $indexStr[-1] : ''))];

            return [ResponseStatus::FOUND, $handler, array_combine($variableNames, $matches)];
        }

        return [ResponseStatus::NOT_FOUND, ResponseStatus::PHRASES[ResponseStatus::NOT_FOUND]];
    }
}
