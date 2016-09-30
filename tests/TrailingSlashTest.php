<?php

namespace Middlewares\Tests;

use Middlewares\TrailingSlash;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response;
use mindplay\middleman\Dispatcher;

class TrailingSlashTest extends \PHPUnit_Framework_TestCase
{
    public function removeProvider()
    {
        return [
            ['/foo/bar', '/foo/bar'],
            ['/foo/bar/', '/foo/bar'],
            ['/', '/'],
            ['', '/'],
        ];
    }

    /**
     * @dataProvider removeProvider
     */
    public function testRemove($url, $result)
    {
        $dispatcher = new Dispatcher([
            new TrailingSlash(),

            function ($request, $next) {
                $response = new Response();
                $response->getBody()->write((string) $request->getUri());

                return $response;
            },
        ]);

        $response = $dispatcher->dispatch(new Request($url));

        $this->assertInstanceOf('Psr\\Http\\Message\\ResponseInterface', $response);
        $this->assertEquals($result, (string) $response->getBody());
    }

    public function addProvider()
    {
        return [
            ['/foo/bar', '/foo/bar/'],
            ['/foo/bar/', '/foo/bar/'],
            ['/', '/'],
            ['', '/'],
            ['/index.html', '/index.html'],
            ['/index', '/index/'],
        ];
    }

    /**
     * @dataProvider addProvider
     */
    public function testAdd($url, $result)
    {
        $dispatcher = new Dispatcher([
            new TrailingSlash(true),

            function ($request, $next) {
                $response = new Response();
                $response->getBody()->write((string) $request->getUri());

                return $response;
            },
        ]);

        $response = $dispatcher->dispatch(new Request($url));

        $this->assertInstanceOf('Psr\\Http\\Message\\ResponseInterface', $response);
        $this->assertEquals($result, (string) $response->getBody());
    }

    public function testRedirect()
    {
        $dispatcher = new Dispatcher([
            (new TrailingSlash())->redirect(),
        ]);

        $response = $dispatcher->dispatch(new Request('/foo/bar/'));

        $this->assertInstanceOf('Psr\\Http\\Message\\ResponseInterface', $response);
        $this->assertEquals(301, (string) $response->getStatusCode());
        $this->assertEquals('/foo/bar', $response->getHeaderLine('location'));
    }
}
