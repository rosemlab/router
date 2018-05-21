<?php

require_once __DIR__ . '/vendor/autoload.php';

function numberToRegex(int $number): string
{
    $numberString = (string)$number;
    $numberLength = strlen($numberString);
    $isOdd = $numberLength % 2;
    $partsCount = $numberHalfLength = (int)ceil($numberLength / 2);
    $leftPart = '';
    $rightPart = '';
    $lastLeftNumber = -1;
//    $lastRightNumber = (int)$numberString[-1] + 1;
    $lastRightNumber = 10;

    do {
        $leftNumber = (int)$numberString[$numberHalfLength - $partsCount];
        $rightNumber = (int)$numberString[$numberHalfLength -$isOdd + $partsCount - 1];

        if ($leftNumber !== ($lastLeftNumber + 1) % 10) {
            $leftPart .= '.*' . $leftNumber;
        } else {
            $leftPart .= $leftNumber;
        }

        $lastLeftNumber = $leftNumber;

        if ($partsCount < 2) {
            if ($isOdd) {
                if ($leftNumber > 9) {
                    if (($leftNumber + 1) % 10 !== $lastRightNumber) {
                        $leftPart .= '.*';
                    }
                } else {
//                    if ($leftNumber + 1 !== $rightNumber) {
//                        $leftPart .= '.*';
//                    }
                }

                continue;
            }

            if (($leftNumber + 1) % 10 !== $rightNumber) {
                $leftPart .= '.*';
            }
        }

        if (($rightNumber + 1) % 10 !== $lastRightNumber) {
            $rightPart = $rightNumber . '.*' . $rightPart;
        } else {
            $rightPart = $rightNumber . $rightPart;
        }

        $lastRightNumber = $rightNumber;
    } while (--$partsCount);

    return $leftPart . rtrim(rtrim($rightPart, '*'), '.');
}

//var_dump(numberToRegex(100)); die;

foreach (range(0, 1000) as $number) {
    echo '<p>' . numberToRegex($number) . '</p>';
    echo '<hr>';
}

die;

$route = new Rosem\Route\RouteCollector();

$route->addRoute('GET', '/user/{name:\w+}', 'get_user');
$route->addRoute('GET', '/post/{id:\d+}', 'get_post');
$result = $route->make('GET', '/post/25');
var_dump($result);
