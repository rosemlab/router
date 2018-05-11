<?php

namespace Rosem\Route;

class RouteChunk implements \ArrayAccess, \Countable
{
    public $regex;
    protected $routes      = [];
    protected $routesLimit = 0;
    protected $regexLength;
    protected $regexLimit  = 0;
    protected $matches     = [];

    protected static $suffix = '/0123456789';

    public function __construct(int $routesLimit = 99, ?int $regexLimit = null)
    {
        $this->routesLimit = $routesLimit;
        $this->regexLimit = $regexLimit ?: (int) ini_get('pcre.backtrack_limit') ?: 1000000;
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

        $indexLength = (int) floor(log10($index) + 1);
        $indexString = (string) $index;

        do {
            $regex = '.*' . $indexString[--$indexLength] . $regex;
        } while ($indexLength);

        return $regex;
    }

    public function addRoute(RouteInterface $route): bool
    {
        $index = \count($this->routes);
        $regex = '(?:' . $route->getRegex() . ')/(' . $this->getIndexRegex($index) . ')';

        if (($this->routesLimit === INF || \count($this->routes) < $this->routesLimit) &&
            ($this->regexLength + \strlen($regex)) < $this->regexLimit
        ) {
            if ($index) {
                $this->regex = "$regex|$this->regex";
                $this->regexLength += \strlen($regex) + 1;
            } else {
                $this->regex = $regex;
                $this->regexLength = \strlen($regex);
            }

            $this->routes[] = $route;

            return true;
        }

        return false;
    }

    public function getRegex(): string
    {
        return $this->regex;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->routes[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->routes[$offset] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        null === $offset ? $this->routes[] = $value : $this->routes[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->routes[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return \count($this->routes);
    }
}
