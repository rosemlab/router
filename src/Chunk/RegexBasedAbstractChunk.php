<?php

namespace Rosem\Route\Chunk;

use ArrayAccess;
use Countable;
use InvalidArgumentException;
use Rosem\Route\ChunkInterface;
use function count;
use function strlen;

abstract class RegexBasedAbstractChunk implements ArrayAccess, Countable, ChunkInterface
{
    protected $routes = [];

    protected $regex;

    protected $routesLimit;

    protected $regexLimit;

    protected $regexLength = 0;

    /**
     * NumberBasedChunk constructor.
     *
     * @param int      $routesLimit
     * @param int|null $regexLimit
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(int $routesLimit, ?int $regexLimit = null)
    {
        if ($routesLimit <= 0) {
            throw new InvalidArgumentException('Limit of routes should be a positive integer number');
        }

        $this->routesLimit = $routesLimit;
        $this->regexLimit = $regexLimit ?: (int)ini_get('pcre.backtrack_limit') ?: 1000000;
    }

    protected function beyondRoutesLimit(int $index): bool
    {
        return $this->routesLimit !== INF && $index >= $this->routesLimit;
    }

    /**
     * @param string $regex
     *
     * @throws \InvalidArgumentException
     */
    protected function verifyRegexLimit(string $regex): void
    {
        if ($this->regexLength + strlen($regex) >= $this->regexLimit) {
            throw new InvalidArgumentException('Your route is too long');
        }
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
        return count($this->routes);
    }
}
