<?php

namespace Rosem\Route\Chunk;

use function count;
use Rosem\Route\RouteInterface;

class NumberBasedChunk extends RegexBasedAbstractChunk
{
    protected const REGEX_ADDITIONAL_LENGTH = 11;

    protected $routeMaxCountLength;

    /**
     * NumberBasedChunk constructor.
     *
     * @param array    $result
     * @param float    $routeMaxCount
     * @param int|null $regexMaxLength
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array &$result, float $routeMaxCount = 999, ?int $regexMaxLength = null)
    {
        parent::__construct($result, $routeMaxCount, $regexMaxLength);

        $this->routeMaxCountLength = (int)floor(log10($routeMaxCount) + 1);
    }

    protected function numberToRegex(int $number): string
    {
        $numberString = (string)$number;
        $numberLength = (int)floor(log10($number) + 1);
        $partsCount = (int)($numberLength / 2);
        $leftPart = '';
        $rightPart = '';
        $lastLeftNumber = -1;
        $lastRightNumber = (int)$numberString[-1];

        do {
            $leftNumber = (int)$numberString[$numberLength - $partsCount - 1];
            $rightNumber = (int)$numberString[$partsCount - $numberLength];
            $leftPart .= ($leftNumber - 1 === $lastLeftNumber ? '(' : '.*(') . $leftNumber;
            $rightPart .= ($rightNumber + 1 === $lastRightNumber ? '' : '.*') . $rightNumber . ')';
            $lastLeftNumber = $leftNumber;
            $lastRightNumber = $rightNumber;
        } while (--$partsCount);

        return $leftPart . $rightPart;
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
        $this->finalRegex = '~^(?|' . $this->regex . ').*/$~';
        $this->routes[] = [$route->getHandler(), $route->getVariableNames()];

        return true;
    }
}
