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
     * @param array    $routeData
     * @param array    $routes
     * @param int      $routeCountPerRegex
     * @param int|null $regexMaxLength
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(
        array &$routeData,
        array &$routes,
        int $routeCountPerRegex = PHP_INT_MAX,
        ?int $regexMaxLength = null
    ) {
        parent::__construct($routeData, $routes, $routeCountPerRegex, $regexMaxLength);

        $routeData[] = '';
    }

    /**
     * @param RouteInterface $route
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function addRoute(RouteInterface $route): void
    {
        $index = count($this->routes);

        if ($index - $this->offset >= $this->routeCountPerRegex) {
            $this->regexTree->clear();
            $this->offset = $index;
            $this->routeData[] = '';
        }

        $this->addRegex($route->getRegex() . '(*:' . $index . ')');
        $this->routeData[count($this->routeData) - 1] = '~^' . $this->regex . '$~sD' . ($this->utf8 ? 'u' : '');
        $this->routes[] = [$route->getHandler(), $route->getVariableNames()];
    }
}
