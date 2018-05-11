<?php

namespace Rosem\Route;

class RouteParser
{
    private const ROUTE_VARIABLE_TOKENS = ['{', '}'];

    private const ROUTE_VARIABLE_REGEX_TOKEN = ':';

    /*    private const ROUTE_SEGMENTS_REGEX = '/(?>\\\)\/|' . self::ROUTE_VARIABLE_TOKENS[0] . '[^\/\s]+/u';*/
    private const ROUTE_SEGMENTS_REGEX =
        '/' . self::ROUTE_VARIABLE_TOKENS[0] .
        '\s*([a-zA-Z_][a-zA-Z0-9_-]*)\s*' . self::ROUTE_VARIABLE_REGEX_TOKEN . '?((?:(?<=\\\)\\/|[^\\/])*)' .
        self::ROUTE_VARIABLE_TOKENS[1] . '/u';

    /**
     * @param string $route
     *
     * @return string
     */
    public function parse(string $route): string
    {
        $regex = preg_replace_callback(self::ROUTE_SEGMENTS_REGEX, function ($matches) {
            if ($matches[2]) {
                return "($matches[2])";
            }

            return '([^/]+)';
        }, $route);

        return $regex;
    }
}
