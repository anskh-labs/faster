<?php

declare(strict_types=1);

namespace Faster\Http\Handler;

use Faster\Helper\Service;
use HttpSoft\Message\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * RequestHandler
 * -----------
 * RequestHandler adopt mechanism from Harmoni
 * @see www.github.com/whohoolabs/harmony
 *
 * @author Khaerul Anas <khaerulanas@live.com>
 * @since v1.0.0
 * @package Faster\Http\Handler
 */
class RequestHandler implements RequestHandlerInterface
{
    private ServerRequestInterface $request;
    private int $currentMiddleware;
    private array $middleware;
    private ResponseInterface $response;
    
    /**
     * __construct
     *
     * @param  array $middlewares
     * @param  ResponseInterface $response
     * @return void
     */
    public function __construct(array $middlewares, ResponseInterface $response)
    {
        foreach ($middlewares as $mid) {
            $this->addMiddleware($mid['middleware'], $mid['id']);
        }
        $this->response = $response;
        $this->currentMiddleware = -1;
    }

    /**
     * @inheritdoc
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->request = Service::request($request);
        $this->currentMiddleware++;

        if (array_key_exists($this->currentMiddleware, $this->middleware) === true) {
            $this->executeMiddleware($this->middleware[$this->currentMiddleware]);
        }

        return $this->response;
    }

    /**
     * @inheritdoc
     */
    public function addMiddleware(MiddlewareInterface $middleware, ?string $id = null): self
    {
        $this->middleware[] = [
            "id" => $id ?? get_class($middleware),
            "middleware" => $middleware,
        ];

        return $this;
    }

    /**
     * @param array<string, MiddlewareInterface> $middlewareArray
     * @return void
     */
    private function executeMiddleware(array $middlewareArray): void
    {
        /** @var MiddlewareInterface $middleware */
        $middleware = $middlewareArray["middleware"];

        $this->response = Service::response($middleware->process($this->request, $this));
    }
}
