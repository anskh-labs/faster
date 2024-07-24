<?php

declare(strict_types=1);

namespace Faster\Http\Middleware;

use Faster\Exception\CsrfFailure;
use Faster\Helper\Service;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * CsrfMiddleware
 * -----------
 * CsrfMiddleware, inspired from https://github.com/slimphp/Slim-Csrf
 *
 * @author Khaerul Anas <khaerulanas@live.com>
 * @since 1.0.0
 * @package Faster\Http\Middleware
 */
class CsrfMiddleware implements MiddlewareInterface
{   
    private ResponseInterface $response;
    private string $tokenNameKey;
    private string $tokenValueKey;
    
    /**
     * __construct
     *
     * @param  ResponseInterface $response
     * @param  string $tokenNameKey
     * @param  string $tokenValueKey
     * @return void
     */
    public function __construct(ResponseInterface $response, string $tokenNameKey = '__csrf_name', string $tokenValueKey = '__csrf_value')
    {
        $this->response = $response;
        $this->tokenNameKey = $tokenNameKey;
        $this->tokenValueKey = $tokenValueKey;
    }
    
    /**
     * process
     *
     * @param  ServerRequestInterface $request
     * @param  RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $body = $request->getParsedBody();
        $name = null;
        $value = null;

        if (is_array($body)) {
            $name = $body[$this->tokenNameKey] ?? null;
            $value = $body[$this->tokenValueKey] ?? null;
        }
        if ($name === null && $value === null) {
            // DELETE request may not have a request body. Supply token by headers
            $name = $request->getHeader($this->tokenNameKey)[0] ?? null;
            $value = $request->getHeader($this->tokenValueKey)[0] ?? null;
        }

        if (in_array($request->getMethod(), ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            $isValid = $this->validateToken($name, $value);
            if ($name === null || $value === null || !$isValid) {
                $request = $this->appendNewTokenToRequest($request);
                throw new CsrfFailure();
            }
        } else {
            // Method is GET/OPTIONS/HEAD/etc, so do not accept the token in the body of this request
            if ($name !== null) {
                throw new CsrfFailure();
            }
        }

        $request = $this->appendNewTokenToRequest($request);

        return $handler->handle($request);
    }    
    /**
     * maskToken
     *
     * @param  string $token
     * @return string
     */
    private function maskToken(string $token): string
    {
        // Key length need to be the same as the length of the token
        $key = random_bytes(strlen($token));
        return base64_encode($key . ($key ^ $token));
    }    
    /**
     * unmaskToken
     *
     * @param  string $maskedToken
     * @return string
     */
    private function unmaskToken(string $maskedToken): string
    {
        $decoded = base64_decode($maskedToken, true);
        if ($decoded === false) {
            return '';
        }
        $tokenLength = strlen($decoded) / 2;
        // If $tokenLength is not an int value, token is invalid (token length and key length need to be the same)
        if (!is_int($tokenLength)) {
            return '';
        }

        $key = substr($decoded, 0, $tokenLength);
        $decodedMaskedToken = substr($decoded, $tokenLength, $tokenLength);

        return $key ^ $decodedMaskedToken;
    }    
    /**
     * validateToken
     *
     * @param  string $name
     * @param  string $value
     * @return bool
     */
    private function validateToken(string $name, string $value): bool
    {
        return Service::session()->validateCsrfToken($name, $this->unmaskToken($value));
    }    
    /**
     * appendNewTokenToRequest
     *
     * @param  ServerRequestInterface $request
     * @return ServerRequestInterface
     */
    private function appendNewTokenToRequest(ServerRequestInterface $request): ServerRequestInterface
    {
        $name = uniqid('csrf');
        $token = $this->maskToken(Service::session()->csrfToken($name));

        return $request->withAttribute($this->tokenNameKey, $name)->withAttribute($this->tokenValueKey, $token);
    }
}