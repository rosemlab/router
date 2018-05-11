<?php

namespace Rosem\Route;

class Route implements RouteInterface
{
    protected $regex;
    protected $method;

    public function __construct(string $method, string $regex)
    {
        $this->method = $method;
        $this->regex = $regex;
    }

    public function getRegex(): string
    {
        return $this->regex;
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}
