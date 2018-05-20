<?php

namespace Rosem\Route\Chunk;

use function count;
use Rosem\Route\RouteInterface;
use function strlen;

class NumberBasedChunk extends RegexBasedAbstractChunk
{
    /**
     * NumberBasedChunk constructor.
     *
     * @param int      $routesLimit
     * @param int|null $regexLimit
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(int $routesLimit = 99, ?int $regexLimit = null)
    {
        parent::__construct($routesLimit, $regexLimit);
    }

    protected function getIndexRegex(int $index): string
    {
        $regex = '';

        if ($index < 10) {
            if ($index) {
                $regex = '.*';
            }

            return $regex . $index;
        }

        $indexLength = (int)floor(log10($index) + 1);
        $indexString = (string)$index;

        do {
            $regex = '.*' . $indexString[--$indexLength] . $regex;
        } while ($indexLength);

        return $regex;
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

        if ($this->beyondRoutesLimit($index)) {
            return false;
        }

        $regex = '(?:' . $route->getRegex() . ')/(' . $this->getIndexRegex($index) . ')';
        $this->verifyRegexLimit($regex);

        if ($index) {
            $this->regex .= '|' . $regex;
            // Why 10? 1 char of symbol `|` and 9 chars of wrap in `getRegex` method
            $this->regexLength += strlen($regex) + 10;
        } else {
            $this->regex = $regex;
            $this->regexLength = strlen($regex);
        }

        $this->routes[] = $route;

        return true;
    }

    public function getRegex(): string
    {
        return '~^(?|' . $this->regex . ')\d*$~';
    }
}
