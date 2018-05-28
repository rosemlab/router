<?php

namespace Rosem\Route\Dispatcher;

use Psrnext\Http\Message\ResponseStatus;

abstract class AbstractDispatcher
{
    protected const ROUTE_FOUND = ResponseStatus::OK;

    protected const ROUTE_NOT_FOUND = ResponseStatus::NOT_FOUND;

    protected const ROUTE_NOT_FOUND_PHRASE = ResponseStatus::PHRASES[ResponseStatus::NOT_FOUND];
}
