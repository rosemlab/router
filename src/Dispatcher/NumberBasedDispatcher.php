<?php

namespace Rosem\Route\Dispatcher;

use Psrnext\Http\Message\ResponseStatus;
use Rosem\Route\ChunkInterface;
use Rosem\Route\DispatcherInterface;

class NumberBasedDispatcher implements DispatcherInterface
{
    protected $suffix = '/';

    public function __construct(int $routesLimit = 99)
    {
        $this->suffix .= str_pad(
            '',
            10 * (int)floor(log10($routesLimit) + 1), // get count of numbers
            '0123456789'
        );
    }

    /**
     * @param ChunkInterface[] $chunkCollection
     * @param string           $uri
     *
     * @return array
     */
    public function dispatch(array $chunkCollection, string $uri): array
    {
        $uri .= $this->suffix;

        foreach ($chunkCollection as $routeChunk) {
            if (!preg_match($routeChunk[ChunkInterface::REGEX], $uri, $matches)) {
                continue;
            }

            unset($matches[key($matches)]);
            $indexStr = array_pop($matches);
            [$handler, $variableNames] = $routeChunk[ChunkInterface::ROUTES][(int)($indexStr[0] . $indexStr[-1])];

            return [ResponseStatus::FOUND, $handler, array_combine($variableNames, $matches)];
        }

        return [ResponseStatus::NOT_FOUND, ResponseStatus::PHRASES[ResponseStatus::NOT_FOUND]];
    }
}