<?php

namespace Rosem\Route\Chunk;

use Rosem\Route\RouteInterface;
use function count;
use function strlen;

class NumberBasedChunk extends RegexBasedAbstractChunk
{
    public const KEY_SUFFIX = 2;

    protected const REGEX_ADDITIONAL_LENGTH = 11;

    protected $routeMaxCountLength;

    protected $regexSegmentsCount;

    /**
     * NumberBasedChunk constructor.
     *
     * @param array    $result
     * @param float    $routeMaxCount
     * @param int|null $regexMaxLength
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array &$result, float $routeMaxCount = 32, ?int $regexMaxLength = null)
    {
        parent::__construct($result, $routeMaxCount, $regexMaxLength);

        $result[self::KEY_SUFFIX] =
            '/' . str_pad('', 10 * strlen((string)$routeMaxCount), '0123456789') . '/';
        $this->routeMaxCountLength = strlen((string)$routeMaxCount);
        $this->regexSegmentsCount = (int)ceil($this->routeMaxCountLength / 2);
    }

    protected function convertNumberToRegex(int $number): string
    {
        $numberString = (string)$number;
        $numberLength = strlen($numberString);
        $numberParts = (int)ceil($numberLength / 2);
        $index = 0;
        $previousLeftNumber = -1;
        $previousRightNumber = 0;
        $leftPart = '';
        $rightPart = '';

        do {
            $leftNumber = (int)$numberString[$index];
            $leftPart .= (($leftNumber !== ($previousLeftNumber + 1) % 10) ? '.*(' : '(') . $leftNumber;
            $previousLeftNumber = $leftNumber;
            $rightNumber = (int)$numberString[-$index - 1];
            $rightPart = (($rightNumber + 1) % 10 !== $previousRightNumber ? ').*' : ')') . $rightPart;
            $previousRightNumber = $rightNumber;
            ++$index;

            if ($numberParts === 1) { // last iteration
                if (!($numberLength % 2)) { // even length number
                    $leftPart .= (($leftNumber + 1) % 10 !== $rightNumber ? '.*' : '') . $rightNumber;
                }

                continue;
            }

            $rightPart = $rightNumber . $rightPart;
        } while (--$numberParts);

        return rtrim($leftPart . $rightPart, '.*');
    }

    /**
     * @param RouteInterface $route
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function addRoute(RouteInterface $route): bool
    {
        $index = count($this->routes);

        if ($this->routeMaxCount !== INF && $index >= $this->routeMaxCount) {
            return false;
        }

        $this->addRegex($index, $route->getRegex() . '/' . $this->convertNumberToRegex($index));
        $this->finalRegex = '~^(?|' . $this->regex . ').*/$~';
        $this->routes[] = [$route->getHandler(), $route->getVariableNames()];

        return true;
    }
}

// Helpers
// 0 - (0)
// 1 - .*(1)
// 2 - .*(2)
// 3 - .*(3)
// 9 - .*(9)
// 10 - .*(1.*0)
// 11 - .*(1.*1)
// 12 - .*(12)
// 13 - .*(1.*3)
// 20 - .*(2.*0)
// 22 - .*(2.*2)
// 23 - .*(23)
// 34 - .*(34)
// 88 - .*(8.*8)
// 89 - .*(89)
// 90 - .*(90)
// 99 - .*(9.*9)
// 100 - .*(1.*(0).*0)
// 101 - .*(1.*(0)1)
// 102 - .*(1.*(0).*2)
// 109 - .*(1.*(0).*9)
// 110 - .*(1.*(1).*0)
// 111 - .*(1.*(1).*1)
// 112 - .*(1.*(1)2)
// 120 - .*(1(2).*0)
// 122 - .*(1(2).*2)
// 123 - .*(1(2)3)
// 1290 - .*(1(2.*9)0)
// 1342 - .*(1.*(34).*2)
// 1357 - .*(1.*(3.*5).*7)
