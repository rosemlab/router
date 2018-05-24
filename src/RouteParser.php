<?php

namespace Rosem\Route;

use function strlen;

class RouteParser
{
    protected const VARIABLE_TOKENS = ['{', '}'];

    protected const VARIABLE_REGEX_TOKEN = ':';

    protected const DEFAULT_DISPATCH_REGEX = '[^/]+';

    private $defaultDispatchRegexLength;

    private $variableSplitRegex;

    public function __construct()
    {
        // add 2 because of round brackets
        $this->defaultDispatchRegexLength = strlen(static::DEFAULT_DISPATCH_REGEX) + 2;
        // {\s*([a-zA-Z_][a-zA-Z0-9_-]*)\s*:?([^\/]*?[^{]*)}
        $this->variableSplitRegex = '/'
        . static::VARIABLE_TOKENS[0]
        . '\s*([[:alpha:]_][[:alnum:]_-]*)\s*' . static::VARIABLE_REGEX_TOKEN
        . '?([^\\/]*?[^' . static::VARIABLE_TOKENS[0] . ']*)'
        . static::VARIABLE_TOKENS[1]
        . '/u';
    }

    /**
     * @param string $route
     *
     * @return array[]
     */
    public function parse(string $route): array
    {
        $variableNames = [];
        $variableRanges = [];
        $index = 0;
        $offset = 0;
        $regex = preg_replace_callback(
            $this->variableSplitRegex,
            function ($matches) use (&$route, &$variableNames, &$variableRanges, &$index, &$offset) {
                $variableNames[] = $matches[1] ?: $index;
                ++$index;
                $variableLength = strlen($matches[0]);

                if ($matches[2]) {
                    $variableRegex = '(' . $matches[2] . ')';
                    $variableRegexLength = strlen($variableRegex);
                } else {
                    $variableRegex = '(' . static::DEFAULT_DISPATCH_REGEX . ')';
                    $variableRegexLength = $this->defaultDispatchRegexLength;
                }

                end($variableRanges);
                $variableRanges[
                    mb_strpos($route, $matches[0], (int)key($variableRanges) - $offset) + $offset
                ] = $variableRegexLength;
                $offset += max($variableLength, $variableRegexLength) - $variableLength;

                return $variableRegex;
            },
            $route
        );

        return [
            [$regex, $variableNames, $variableRanges],
        ];
    }
}
