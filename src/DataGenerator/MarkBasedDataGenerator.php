<?php

namespace Rosem\Route\DataGenerator;

use Rosem\Route\RouteInterface;

use function count;

class MarkBasedDataGenerator extends AbstractRegexBasedDataGenerator
{
    protected $offset = 0;

    /**
     * MarkBasedChunk constructor.
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
        int $routeCountPerRegex = PHP_INT_MAX,
        ?int $regexMaxLength = null
    ) {
        parent::__construct($routeMap, $routeData, $routeCountPerRegex, $regexMaxLength);

        $routeMap[] = '';
    }

    /**
     * @param RouteInterface $route
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function addRoute(RouteInterface $route): void
    {
        $index = count($this->routeData);

        if ($index - $this->offset >= $this->routeCountPerRegex) {
            $this->regexTree->clear();
            $this->offset = $index;
            $this->routeMap[] = '';
        }

        $this->addRegex($route->getRegex() . '(*:' . $index . ')');
        $this->routeMap[count($this->routeMap) - 1] = '~^' . $this->regex . '$~sD' . ($this->utf8 ? 'u' : '');
        $middleware = &$route->getMiddlewareReference();
        $this->routeData[] = [$route->getHandler(), &$middleware, $route->getVariableNames()];
    }
}
