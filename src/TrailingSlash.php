<?php

namespace Middlewares;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Interop\Http\Middleware\MiddlewareInterface;
use Interop\Http\Middleware\DelegateInterface;

class TrailingSlash implements MiddlewareInterface
{
    /**
     * @var bool Add or remove the slash
     */
    private $trailingSlash;

    /**
     * @var bool Returns a redirect response or not
     */
    private $redirect;

    /**
     * Configure whether add or remove the slash.
     *
     * @param bool $trailingSlash
     */
    public function __construct($trailingSlash = false)
    {
        $this->trailingSlash = (bool) $trailingSlash;
    }

    /**
     * Whether returns a 301 response to the new path.
     *
     * @param bool $redirect
     *
     * @return self
     */
    public function redirect($redirect = true)
    {
        $this->redirect = (bool) $redirect;

        return $this;
    }

    /**
     * Process a client request and return a response.
     *
     * @param RequestInterface  $request
     * @param DelegateInterface $delegate
     *
     * @return ResponseInterface
     */
    public function process(RequestInterface $request, DelegateInterface $delegate)
    {
        $uri = $request->getUri();
        $path = $this->normalize($uri->getPath());

        if ($this->redirect && ($uri->getPath() !== $path)) {
            return Utils\Factory::createResponse(301)
                ->withHeader('Location', (string) $uri->withPath($path));
        }

        return $delegate->process($request->withUri($uri->withPath($path)));
    }

    /**
     * Normalize the trailing slash.
     *
     * @param string $path
     *
     * @return string
     */
    private function normalize($path)
    {
        if ($path === '') {
            return '/';
        }

        if (strlen($path) > 1) {
            if ($this->trailingSlash) {
                if (substr($path, -1) !== '/' && !pathinfo($path, PATHINFO_EXTENSION)) {
                    return $path.'/';
                }
            } else {
                return rtrim($path, '/');
            }
        }

        return $path;
    }
}
