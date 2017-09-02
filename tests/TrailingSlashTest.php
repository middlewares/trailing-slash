<?php

namespace Middlewares\Tests;

use Middlewares\TrailingSlash;
use Middlewares\Utils\Dispatcher;
use Middlewares\Utils\Factory;
use PHPUnit\Framework\TestCase;

class TrailingSlashTest extends TestCase
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
     * @param mixed $uri
     * @param mixed $result
     */
    public function testRemove($uri, $result)
    {
        $request = Factory::createServerRequest([], 'GET', $uri);

        $response = Dispatcher::run([
            new TrailingSlash(),

            function ($request, $next) {
                echo $request->getUri();
            },
        ], $request);

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
     * @param mixed $uri
     * @param mixed $result
     */
    public function testAdd($uri, $result)
    {
        $request = Factory::createServerRequest([], 'GET', $uri);

        $response = Dispatcher::run([
            new TrailingSlash(true),

            function ($request, $next) {
                echo $request->getUri();
            },
        ], $request);

        $this->assertInstanceOf('Psr\\Http\\Message\\ResponseInterface', $response);
        $this->assertEquals($result, (string) $response->getBody());
    }

    public function testRedirect()
    {
        $request = Factory::createServerRequest([], 'GET', '/foo/bar/');

        $response = Dispatcher::run([
            (new TrailingSlash())->redirect(),
        ], $request);

        $this->assertInstanceOf('Psr\\Http\\Message\\ResponseInterface', $response);
        $this->assertEquals(301, (string) $response->getStatusCode());
        $this->assertEquals('/foo/bar', $response->getHeaderLine('location'));
    }
}
