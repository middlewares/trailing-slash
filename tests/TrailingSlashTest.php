<?php
declare(strict_types = 1);

namespace Middlewares\Tests;

use Middlewares\TrailingSlash;
use Middlewares\Utils\Dispatcher;
use Middlewares\Utils\Factory;
use PHPUnit\Framework\TestCase;

class TrailingSlashTest extends TestCase
{
    /**
     * @return array<string[]>
     */
    public function removeProvider(): array
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
    public function testRemove(string $uri, string $result): void
    {
        $request = Factory::createServerRequest('GET', $uri);

        $response = Dispatcher::run([
            new TrailingSlash(),

            function ($request, $next) {
                echo $request->getUri();
            },
        ], $request);

        $this->assertEquals($result, (string) $response->getBody());
    }

    /**
     * @return array<string[]>
     */
    public function addProvider(): array
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
    public function testAdd(string $uri, string $result): void
    {
        $request = Factory::createServerRequest('GET', $uri);

        $response = Dispatcher::run([
            new TrailingSlash(true),

            function ($request, $next) {
                echo $request->getUri();
            },
        ], $request);

        $this->assertEquals($result, (string) $response->getBody());
    }

    public function testRedirect(): void
    {
        $request = Factory::createServerRequest('GET', '/foo/bar/');

        $response = Dispatcher::run([
            (new TrailingSlash())->redirect(),
        ], $request);

        $this->assertEquals(301, (string) $response->getStatusCode());
        $this->assertEquals('/foo/bar', $response->getHeaderLine('location'));
    }
}
