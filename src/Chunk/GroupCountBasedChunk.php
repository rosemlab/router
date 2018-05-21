<?php

namespace Rosem\Route\Chunk;

use Rosem\Route\RouteInterface;
use function count;

class GroupCountBasedChunk extends RegexBasedAbstractChunk
{
    protected const REGEX_ADDITIONAL_LENGTH = 8;

    /**
     * GroupCountBasedChunk constructor.
     *
     * @param array    $result
     * @param float    $routeMaxCount
     * @param int|null $regexMaxLength
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array &$result, float $routeMaxCount = 10, ?int $regexMaxLength = null)
    {
        parent::__construct($result, $routeMaxCount, $regexMaxLength);
    }

    /**
     * @param RouteInterface $route
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function addRoute(RouteInterface $route): bool
    {
        $index = count($this->routes);

        if ($this->routeMaxCount !== INF && $index >= $this->routeMaxCount) {
            return false;
        }

        $variableCount = count($route->getVariableNames());
        $regex = $route->getRegex()
            . str_repeat('()', max($index + 1, $variableCount) - $variableCount);
        $this->addRegex($index, $regex);
        $this->finalRegex = '~^(?|' . $this->regex . ')$~';
        // 1 for first regex matching and 1 for index
        $this->routes[$index + 2] = [$route->getHandler(), $route->getVariableNames()];

        return true;
    }
}
