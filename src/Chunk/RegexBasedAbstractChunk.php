<?php

namespace Rosem\Route\Chunk;

use InvalidArgumentException;
use Rosem\Route\ChunkInterface;
use function strlen;

abstract class RegexBasedAbstractChunk implements ChunkInterface
{
    protected const REGEX_ADDITIONAL_LENGTH = 8;

    protected $routes = [];

    protected $regex = '';

    protected $finalRegex = '';

    protected $routesLimit;

    protected $regexLimit;

    protected $regexLength = 0;

    /**
     * NumberBasedChunk constructor.
     *
     * @param array    $result
     * @param float    $routesLimit
     * @param int|null $regexLimit
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array &$result, float $routesLimit, ?int $regexLimit = null)
    {
        if ($routesLimit <= 0) {
            throw new InvalidArgumentException('Limit of routes should be a positive integer number');
        }

        $result = [
            ChunkInterface::REGEX => &$this->finalRegex,
            ChunkInterface::ROUTES => &$this->routes,
        ];
        $this->routesLimit = $routesLimit;
        $this->regexLimit = $regexLimit ?: (int)ini_get('pcre.backtrack_limit') ?: 1000000;
    }

    /**
     * @param int    $index
     * @param string $regex
     *
     * @throws \InvalidArgumentException
     */
    protected function addRegex(int $index, string $regex): void
    {
        $regexLength = strlen($regex);
        $regexFinalLength = $regexLength + static::REGEX_ADDITIONAL_LENGTH;

        if ($this->regexLength + $regexFinalLength + /* 1 char of symbol `|` */ (bool)$index > $this->regexLimit) {
            throw new InvalidArgumentException('Your route is too long');
        }

        if ($index) {
            $this->regex .= '|' . $regex;
            $this->regexLength += $regexFinalLength;
        } else {
            $this->regex = $regex;
            $this->regexLength = $regexLength;
        }
    }
}
