<?php

namespace Rosem\Route\DataGenerator;

use Rosem\Route\RouteInterface;
use function count;

class GroupCountBasedDataGenerator extends AbstractRegexBasedDataGenerator
{
    public const KEY_REGEX = 0;

    public const KEY_OFFSET = 1;

    protected const REGEX_ADDITIONAL_LENGTH = 8;

    protected $chunkCount = 0;

    protected $groupCount = 0;

    /**
     * GroupCountBasedChunk constructor.
     *
     * @param int      $routeCountPerRegex
     * @param int|null $regexMaxLength
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(
        int $routeCountPerRegex = 10,
        ?int $regexMaxLength = null
    ) {
        parent::__construct($routeCountPerRegex, $regexMaxLength);

        $this->routeMap[] = [
            self::KEY_REGEX => '',
            self::KEY_OFFSET => $this->groupCount,
        ];
    }

    /**
     * @param RouteInterface $route
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function addRoute(RouteInterface $route): void
    {
        $offset = $this->routeCountPerRegex * $this->chunkCount;

        if (count($this->routeData) - $offset >= $this->routeCountPerRegex) {
            $this->regexTree->clear();
            $this->groupCount = 0;
            ++$this->chunkCount;
            $offset += $this->routeCountPerRegex;
            $this->routeMap[] = [
                self::KEY_REGEX => '',
                self::KEY_OFFSET => $offset,
            ];
        }

        $variableCount = count($route->getVariableNames());
        $this->groupCount = max($this->groupCount, $variableCount);
        // TODO: check if route regex has groups
        $this->addRegex($route->getRegex() . str_repeat('()', $this->groupCount - $variableCount));
        $this->routeMap[count($this->routeMap) - 1][self::KEY_REGEX] =
            '~^' . $this->regex . '$~sD' . ($this->utf8 ? 'u' : '');
        ++$this->groupCount; // +1 for first regex matching / next route index
        $middleware = &$route->getMiddlewareListReference();
        $this->routeData[$offset + $this->groupCount] =
            [$route->getHandler(), &$middleware, $route->getVariableNames()];
    }
}
