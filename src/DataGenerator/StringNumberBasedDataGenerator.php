<?php

namespace Rosem\Route\DataGenerator;

use Rosem\Route\RouteInterface;
use function count;
use function strlen;

class StringNumberBasedDataGenerator extends AbstractRegexBasedDataGenerator
{
    public const KEY_REGEX = 0;

    public const KEY_SUFFIX = 1;

    public const KEY_SEGMENT_COUNT = 2;

    protected const REGEX_ADDITIONAL_LENGTH = 11;

    protected $offset = 0;

    /**
     * NumberBasedChunk constructor.
     *
     * @param array    $routeData
     * @param array    $routes
     * @param int      $routeCountPerRegex
     * @param int|null $regexMaxLength
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(
        array &$routeData,
        array &$routes,
        int $routeCountPerRegex = 100,
        ?int $regexMaxLength = null
    ) {
        parent::__construct($routeData, $routes, $routeCountPerRegex, $regexMaxLength);

        $routeMaxCountLength = strlen((string)($routeCountPerRegex - 1));
        $routeData[] = [
            self::KEY_REGEX => '',
            self::KEY_SUFFIX =>
                '/' . str_pad('', 10 * $routeMaxCountLength, '0123456789') . '/',
            self::KEY_SEGMENT_COUNT => (int)ceil($routeMaxCountLength / 2),
        ];
    }

    /**
     * @param RouteInterface $route
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function addRoute(RouteInterface $route): void
    {
        $index = count($this->routes);

        if ($index - $this->offset >= $this->routeCountPerRegex) {
            $this->regexTree->clear();
            $this->offset = $index;
            $this->routeCountPerRegex = 900; // TODO: auto-generate
            $routeMaxCountLength = strlen((string)($this->routeCountPerRegex - 1));
            $this->routeData[] = [
                self::KEY_REGEX => '',
                self::KEY_SUFFIX =>
                    '/' . str_pad('', 10 * $routeMaxCountLength, '0123456789') . '/',
                self::KEY_SEGMENT_COUNT => (int)ceil($routeMaxCountLength / 2),
            ];
        }

        $this->addRegex($route->getRegex() . '/' . $this->convertNumberToRegex($index));
        $this->routeData[count($this->routeData) - 1][self::KEY_REGEX] =
            '~^' . $this->regex . '.*/$~sD' . ($this->utf8 ? 'u' : '');
        $this->routes[] = [$route->getHandler(), $route->getVariableNames()];
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
}
