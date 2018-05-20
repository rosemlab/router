<?php

namespace Rosem\Route;

interface RouteInterface
{
    public function getMethod(): string;
    public function getHandler();
    public function getRegex(): string;
    public function getVariableNames(): array;
}
