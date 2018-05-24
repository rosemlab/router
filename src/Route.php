<?php

namespace Rosem\Route;

class Route implements RouteInterface
{
    protected $method;
    protected $handler;
    protected $regex;
    protected $variableNames;
    protected $variableRanges;

    public function __construct(string $method, $handler, array $data)
    {
        $this->method = $method;
        $this->handler = $handler;
        $this->regex = $data[0];
        $this->variableNames = $data[1];
        $this->variableRanges = $data[2];
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

    public function getVariableRanges(): array
    {
        return $this->variableRanges;
    }
}
