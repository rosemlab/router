<?php

namespace Rosem\Route;

class Route implements RouteInterface
{
    protected $method;
    protected $handler;
    protected $regex;
    protected $variableNames;

    public function __construct(string $method, $handler, array $data)
    {
        $this->method = $method;
        $this->handler = $handler;
        $this->regex = $data[RouteParser::DATA_KEY_REGEX];
        $this->variableNames = $data[RouteParser::DATA_KEY_VARIABLE_NAMES];
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
