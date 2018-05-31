<?php

namespace Rosem\Route\DataGenerator;

use InvalidArgumentException;
use Rosem\Route\DataGeneratorInterface;
use Rosem\Route\RegexTreeNode;
use function strlen;

abstract class AbstractRegexBasedDataGenerator implements DataGeneratorInterface
{
    protected const REGEX_ADDITIONAL_LENGTH = 8;

    protected $routeMap;

    protected $routeData;

    protected $routeCountPerRegex;

    protected $regex = '';

    protected $regexMaxLength;

    protected $regexLength = 0;

    /**
     * @var RegexTreeNode
     */
    protected $regexTree;

    protected $utf8;

    /**
     * NumberBasedChunk constructor.
     *
     * @param array    $routeMap
     * @param array    $routeData
     * @param int      $routeCountPerRegex
     * @param int|null $regexMaxLength
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(
        array &$routeMap,
        array &$routeData,
        int $routeCountPerRegex,
        ?int $regexMaxLength = null
    ) {
        if ($routeCountPerRegex <= 0) {
            throw new InvalidArgumentException('Limit of routes should be a positive integer number');
        }

        $this->routeMap = &$routeMap;
        $this->routeData = &$routeData;
        $this->routeCountPerRegex = $routeCountPerRegex;
        $this->regexMaxLength = $regexMaxLength ?: (int)ini_get('pcre.backtrack_limit') ?: 1000000;
        $this->regexTree = new RegexTreeNode();
    }

    public function useUtf8(bool $use = true): void
    {
        $this->utf8 = $use;
    }

    /**
     * @param string $regex
     *
     * @throws \InvalidArgumentException
     */
    protected function addRegex(string $regex): void
    {
        $regexLength = strlen($regex);
        $regexFinalLength = $regexLength + static::REGEX_ADDITIONAL_LENGTH;

        if ($this->regexLength + $regexFinalLength > $this->regexMaxLength) {
            throw new InvalidArgumentException('Your route is too long');
        }

        $this->regexTree->addRegex($regex);
        $this->regex = $this->regexTree->getRegex();
    }
}
