<?php

declare(strict_types=1);

namespace Faster\Http;

use HttpSoft\Emitter\EmitterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Application
 * -----------
 * Application
 *
 * @author Khaerul Anas <khaerulanas@live.com>
 * @since 1.0.0
 * @package Faster\Http
 */
class Application
{
    private ?EmitterInterface $emitter = null;
    private ?ServerRequestInterface $request = null;
    private ?RequestHandlerInterface $requestHandler = null;
    private ?ResponseInterface $response = null;
    
    
    /**
     * __construct
     *
     * @param  RequestHandlerInterface $requestHandler
     * @param  EmitterInterface $emitter
     * @param  ServerRequestInterface $request
     * @param  ResponseInterface $response
     * @return void
     */
    public function __construct(RequestHandlerInterface $requestHandler, EmitterInterface $emitter, ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->requestHandler = $requestHandler;
        $this->emitter = $emitter;
        $this->request = $request;
        $this->response = $response;
    }
    
    /**
     * run
     *
     * @return void
     */
    public function run(): void
    {
        $this->response = $this->requestHandler->handle($this->request);

        if (headers_sent() === false) {
            $this->emitter->emit($this->response);
        }
    }
}
