<?php

declare(strict_types=1);

namespace Faster\Http\Middleware;

use FastRoute\Dispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Faster\Exception\MethodNotAllowed;
use Faster\Exception\RouteNotFound;
use Faster\Http\Router\RouterInterface;

/**
 * FastRouteMiddleware
 * -----------
 * FastRouteMiddleware
 *
 * @author Khaerul Anas <khaerulanas@live.com>
 * @since 1.0.0
 * @package Faster\Http\Middleware
 */
class FastRouteMiddleware implements MiddlewareInterface
{
    private string $actionAttribute;
    private RouterInterface $router;

    /**
     * __construct
     *
     * @param  \Faster\Http\Router\RouterInterface $router
     * @param  array $router
     * @return void
     */
    public function __construct(RouterInterface $router, string $actionAttribute = '__action')
    {
        $this->router = $router;
        $this->actionAttribute = $actionAttribute;
    }

    /**
     * @inheritdoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $this->routeRequest($request);

        return $handler->handle($request);
    }

    /**
     * routeRequest
     *
     * @param  ServerRequestInterface $request
     * @return ServerRequestInterface
     */
    private function routeRequest(ServerRequestInterface $request): ServerRequestInterface
    {
        $fastRoute = $this->router->getDispatcher();

        $path = $request->getUri()->getPath();
        $route = $fastRoute->dispatch($request->getMethod(), $path);

        if ($route[0] === Dispatcher::NOT_FOUND) {
            throw new RouteNotFound($request->getUri()->getPath());
        }

        if ($route[0] === Dispatcher::METHOD_NOT_ALLOWED) {
            throw new MethodNotAllowed($request->getMethod());
        }

        foreach ($route[2] as $name => $value) {
            $request = $request->withAttribute($name, $value);
        }

        $request = $request->withAttribute($this->actionAttribute, $route[1]);

        return $request;
    }
}
