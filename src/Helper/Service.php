<?php

declare(strict_types=1);

namespace Faster\Helper;

use Faster\Http\Auth\UserPrincipalInterface;
use Faster\Http\Session\SessionInterface;
use HttpSoft\Message\Response;
use HttpSoft\ServerRequest\ServerRequestCreator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Service
 * -----------
 * Class for helping access service component
 *
 * @author Khaerul Anas <khaerulanas@live.com>
 * @since 1.0.0
 * @package Faster\Helper
 */
class Service
{
    protected static ?ServerRequestInterface $request = null;
    protected static ?ResponseInterface $response = null;

    /**
     * request
     *
     * @param  ?ServerRequestInterface $request
     * @return ServerRequestInterface
     */
    public static function request(?ServerRequestInterface $request = null): ServerRequestInterface
    {
        if ($request !== null) {
            static::$request = $request;
        }
        if (static::$request === null) {
            static::$request = ServerRequestCreator::createFromGlobals();
        }

        return static::$request;
    }
    /**
     * response
     *
     * @param  ?ResponseInterface $response
     * @return ResponseInterface
     */
    public static function response(?ResponseInterface $response = null): ResponseInterface
    {
        if ($response !== null) {
            static::$response = $response;
        }
        if (static::$response === null) {
            static::$response = new Response();
        }

        return static::$response;
    }
    /**
     * session
     *
     * @param  string $sessionAttribute
     * @return SessionInterface
     */
    public static function session(string $sessionAttribute = '__session'): SessionInterface
    {
        $session = static::$request->getAttribute($sessionAttribute);
        if($session instanceof SessionInterface){
            return $session;
        }

        throw new \Exception("Session not found.");
    }
    /**
     * user
     *
     * @param  string $userAttribute
     * @return UserPrincipalInterface
     */
    public static function user(string $userAttribute = '__user'): UserPrincipalInterface
    {
        $user = static::$request->getAttribute($userAttribute);
        if($user instanceof UserPrincipalInterface){
            return $user;
        }

        throw new \Exception("User which implements UserPrincipalInterface not found.");
    }
    /**
     * sanitize
     *
     * @param  ServerRequestInterface $request
     * @param  string|array $except
     * @return array
     */
    public static function sanitize(ServerRequestInterface $request, $except = ''): array
    {
        $data = [];
        if (is_string($except) && $except) {
            $except = [$except];
        }
        if ($request->getMethod() === 'POST') {
            foreach ($request->getParsedBody() as $key => $value) {
                if ($except && in_array($key, $except)) {
                    $data[$key] = $value;
                } else {
                    if (is_array($value)) {
                        $items = [];
                        foreach ($value as $item) {
                            $items[] = htmlspecialchars($item);
                        }
                        $data[$key] = $items;
                    } else {
                        $data[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        } elseif ($request->getMethod() === 'GET') {
            foreach ($request->getQueryParams() as $key => $value) {
                if ($except && in_array($key, $except)) {
                    $data[$key] = $value;
                } else {
                    if (is_array($value)) {
                        $items = [];
                        foreach ($value as $item) {
                            $items[] = htmlspecialchars($item);
                        }
                        $data[$key] = $items;
                    } else {
                        $data[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }

        return $data;
    }
}
