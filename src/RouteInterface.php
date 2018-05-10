<?php

namespace Rosem\Route;

interface RouteInterface
{
    public function getUri(): string;
    public function getMethod(): string;
}
