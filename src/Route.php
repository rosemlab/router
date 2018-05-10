<?php

namespace Rosem\Route;

class Route implements RouteInterface
{
    protected $regex;
    protected $allowedMethods;

    public function __construct(array $allowedMethods, string $regex)
    {
        $this->allowedMethods = $allowedMethods;
        $this->regex = $regex;
    }

    public function getRegex(): string
    {
        return $this->regex;
    }

    public function getAllowedMethods(): array
    {
        return $this->allowedMethods;
    }
}
