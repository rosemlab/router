<?php

namespace Rosem\Route\Dispatcher;

use Psrnext\Http\Message\ResponseStatus;

abstract class AbstractDispatcher
{
    public const ROUTE_FOUND = ResponseStatus::OK;

    public const ROUTE_NOT_FOUND = ResponseStatus::NOT_FOUND;

    public const ROUTE_NOT_FOUND_PHRASE = ResponseStatus::PHRASES[ResponseStatus::NOT_FOUND];
}
