<?php

namespace Rosem\Route;

function regexHasCapturingGroups(string $regex): bool
{
    return (bool)preg_match('/(?:\(\?\(|\[[^\]\\\\]*(?:\\\\.[^\]\\\\]*)*\]|\\\\.)(*SKIP)(*FAIL)|'
        . '(?<!\(\?\(DEFINE\))\((?!\?(?!<(?![!=])|P<|\')|\*)/', $regex);
}
