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
     * @param float    $routesLimit
     * @param int|null $regexLimit
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array &$result, float $routesLimit = 10, ?int $regexLimit = null)
    {
        parent::__construct($result, $routesLimit, $regexLimit);
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

        if ($this->routesLimit !== INF && $index >= $this->routesLimit) {
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
