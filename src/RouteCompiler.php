<?php

namespace Rosem\Route;

class RouteCompiler implements RouteCompilerInterface
{
    /**
     * @var RouteParserInterface
     */
    protected $parser;

    public function __construct(RouteParserInterface $parser)
    {
        $this->parser = $parser;
    }

    public function compile(array $methods, string $routePattern, string $handler): RouteInterface
    {
        return new Route($methods, $handler, $routePattern, ...$this->parser->parse($routePattern)[0]);
    }
}
