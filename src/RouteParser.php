<?php

namespace Rosem\Route;

class RouteParser
{

    const NAME_REGEX = '\s*([a-zA-Z_][a-zA-Z0-9_-]*)\s*';
    private const ROUTE_REGEX_TOKEN = ':';

    private const ROUTE_VARIABLE_TOKENS = ['{', '}'];

    private const ROUTE_SEGMENTS_REGEX = '/(?>\\\)\/|' . self::ROUTE_VARIABLE_TOKENS[0] . '[^\/\s]+/u';

    public function parse(string $route)
    {

    }
}
