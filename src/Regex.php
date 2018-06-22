<?php

namespace Rosem\Route;

class Regex
{
    public const PREG_STATUS_PHRASES = [
        PREG_NO_ERROR => 'No errors',
        PREG_INTERNAL_ERROR => 'There was an internal PCRE error',
        PREG_BACKTRACK_LIMIT_ERROR => 'Backtrack limit was exhausted',
        PREG_RECURSION_LIMIT_ERROR => 'Recursion limit was exhausted',
        PREG_BAD_UTF8_ERROR => 'The offset did not correspond to the begin of a valid UTF-8 code point',
        PREG_BAD_UTF8_OFFSET_ERROR => 'Malformed UTF-8 data',
    ];

    /**
     * @var string
     */
    protected $regex;

    public function __construct(string $regex)
    {
        $this->regex = $regex;
    }

    public function isValid(): bool
    {
        return @preg_match($this->regex, null) !== false;
    }

    public function getLastError(): string
    {
        return self::PREG_STATUS_PHRASES[preg_last_error()];
    }

    public function hasCapturingGroups(): bool
    {
        return (bool)preg_match('/(?:\(\?\(|\[[^\]\\\\]*(?:\\\\.[^\]\\\\]*)*\]|\\\\.)(*SKIP)(*FAIL)|'
            . '(?<!\(\?\(DEFINE\))\((?!\?(?!<(?![!=])|P<|\')|\*)/', $this->regex);
    }
}
