<?php

namespace Rosem\Route;

interface DispatcherInterface
{
    public function dispatch(array $chunkCollection, string $uri): array;
}
