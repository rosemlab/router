<?php

namespace Rosem\Route;

class RouteParser
{
    protected const VARIABLE_TOKENS = ['{', '}'];

    protected const VARIABLE_REGEX_TOKEN = ':';

    private const SEGMENT_REGEX = '/'
    . self::VARIABLE_TOKENS[0]
    . '\s*([a-zA-Z_][a-zA-Z0-9_-]*)\s*' . self::VARIABLE_REGEX_TOKEN . '?((?:(?<=\\\)\\/|[^\\/])*)'
    . self::VARIABLE_TOKENS[1]
    . '/u';

    private const DEFAULT_DISPATCH_REGEX = '[^/]+';

    /**
     * @param string $route
     *
     * @return array[]
     */
    public function parse(string $route): array
    {
        $variableNames = [];
        $index = 0;
        $regex = preg_replace_callback(self::SEGMENT_REGEX, function ($matches) use (&$variableNames, &$index) {
            $variableNames[] = $matches[1] ?: $index;
            ++$index;

            if ($matches[2]) {
                return '(' . $matches[2] . ')';
            }

            return '(' . self::DEFAULT_DISPATCH_REGEX . ')';
        }, $route);

        return [[$regex, $variableNames]];
    }
}
