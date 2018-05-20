<?php

namespace Rosem\Route\Chunk;

use function count;
use Rosem\Route\RouteInterface;

class NumberBasedChunk extends RegexBasedAbstractChunk
{
    protected const REGEX_ADDITIONAL_LENGTH = 11;

    /**
     * NumberBasedChunk constructor.
     *
     * @param array    $result
     * @param float    $routesLimit
     * @param int|null $regexLimit
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array &$result, float $routesLimit = INF, ?int $regexLimit = null)
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

        $regex = '';

        if ($index < 10) {
            if ($index) {
                $regex = '.*';
            }

            $regex .= $index;
        } else {
            $indexLength = (int)floor(log10($index) + 1);
            $indexString = (string)$index;

            do {
                $regex = '.*' . $indexString[--$indexLength] . $regex;
            } while ($indexLength);
        }

        $this->addRegex($index, $route->getRegex() . '/(' . $regex  . ')');
        $this->finalRegex = '~^(?|' . $this->regex . ')\d*$~';
        $this->routes[] = [$route->getHandler(), $route->getVariableNames()];

        return true;
    }
}
