<?php

namespace Rosem\Route;

interface RouteInterface
{
    public function getRegex(): string;
    public function getMethod(): string;
}
