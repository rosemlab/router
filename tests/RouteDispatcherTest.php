<?php

namespace Rosem\Router;

class RouteDispatcherTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var RouteDispatcher
     */
    protected $routeDispatcher;

    public function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();

        $this->routeDispatcher = new RouteDispatcher();
    }

    /**
     * Test that true does in fact equal true
     */
    public function testDispatch(): void
    {
        $result = $this->routeDispatcher->dispatch('GET', '/');
        $this->assertEquals(['GET', '/'], $result);
    }
}
