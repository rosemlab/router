<?php

namespace Rosem\Route;

class Route implements RouteInterface
{
    protected $method;
    protected $handler;
    protected $regex;
    protected $variableNames;

    public function __construct(string $method, $handler, string $regex, array $variableNames)
    {
        $this->method = $method;
        $this->handler = $handler;
        $this->regex = $regex;
        $this->variableNames = $variableNames;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getHandler()
    {
        return $this->handler;
    }

    public function getRegex(): string
    {
        return $this->regex;
    }

    public function getVariableNames(): array
    {
        return $this->variableNames;
    }
}
