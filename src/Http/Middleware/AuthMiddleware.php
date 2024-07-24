<?php

declare(strict_types=1);

namespace Faster\Http\Middleware;

use Faster\Helper\Service;
use Faster\Http\Auth\AuthModelInterface;
use Faster\Http\Auth\AuthProviderInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Faster\Http\Auth\UserIdentity;
use Faster\Http\Auth\UserPrincipal;
use Faster\Http\Auth\UserPrincipalInterface;

/**
 * AuthMiddleware
 * -----------
 * AuthMiddleware
 *
 * @author Khaerul Anas <anasikova@gmail.com>
 * @since v1.0.0
 * @package Faster\Http\Middleware
 */
class AuthMiddleware implements MiddlewareInterface
{
    private AuthProviderInterface $authProvider;
    private AuthModelInterface $authModel;
    private string $userAttribute;
    private string $userIdAttribute = '__user_id';
    private string $userHashAttribute = '__user_hash';
     
    /**
     * __construct
     *
     * @param  AuthProviderInterface $authProvider
     * @param  AuthModelInterface $userModel
     * @param  string $userAttribute
     * @return void
     */
    public function __construct(AuthProviderInterface $authProvider, AuthModelInterface $authModel, string $userAttribute = '__user')
    {
        $this->authProvider = $authProvider;
        $this->authModel = $authModel;
        $this->userAttribute = $userAttribute;
    }
    
    /**
     * process
     *
     * @param  mixed $request
     * @param  mixed $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $userId = Service::session()->get($this->userIdAttribute);
        $userHash = Service::session()->get($this->userHashAttribute);
        $user = $this->validateUser($userId, $userHash);
        $request = $request->withAttribute($this->userAttribute, $user);

        return $handler->handle($request);
    }
    
    /**
     * validateUser
     *
     * @param  string|int|null $userId
     * @param  ?string $userHash
     * @return UserPrincipalInterface
     */
    public function validateUser($userId = null, ?string $userHash = null): UserPrincipalInterface
    {
        if ($userId !== null && $userHash !== null) {
            $user = $this->authModel->getUser($userId);
            if ($user) {
                if ($this->authModel->validateHash($user, $userHash)) {
                    $roles = $this->authModel->getRoles($user);
                    $permissions = [];
                    if ($roles) {
                        $rolePermissions = $this->authProvider->getPermissions();
                        if($rolePermissions){
                            foreach ($roles as $role){
                                $permissions = array_merge($permissions, $rolePermissions[$role]);
                            }
                        }
                        
                    }
                    return new UserPrincipal($this->authProvider, new UserIdentity($userId, $roles, $permissions, $user));
                }
            }
            Service::session()->unset($this->userIdAttribute);
            Service::session()->unset($this->userHashAttribute);
        }

        return new UserPrincipal($this->authProvider);
    }
}
