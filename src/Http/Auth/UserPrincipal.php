<?php

declare(strict_types=1);

namespace Faster\Http\Auth;

/**
 * UserPrincipal
 * -----------
 * UserPrincipal
 *
 * @author Khaerul Anas <khaerulanas@live.com>
 * @since 1.0.0
 * @package Faster\Http\Auth
 */
class UserPrincipal implements UserPrincipalInterface
{
    private AuthProviderInterface $provider;
    private UserIdentityInterface $identity;
        
    /**
     * __construct
     *
     * @param  ?AuthProviderInterface $provider
     * @param  ?UserIdentityInterface $identity
     * @return void
     */
    public function __construct(?AuthProviderInterface $provider = null, ?UserIdentityInterface $identity = null)
    {
        $this->provider = $provider ?? make(AuthProvider::class);
        $this->identity = $identity ?? make(AnonymousIdentity::class);
    }
    /**
     * @inheritdoc
     */
    public function getProvider(): AuthProviderInterface
    {
        return $this->provider;
    }
   /**
     * @inheritdoc
     */
    public function getIdentity(): UserIdentityInterface
    {
        return $this->identity;
    }    
    /**
     * @inheritdoc
     */
    public function hasRole($role): bool
    {
        if(is_string($role)){
            return in_array($role, $this->identity->getRoles());
        }elseif(is_array($role)){
            foreach($role as $r){
                if($this->hasRole($r)){
                    return true;
                }
            }
        }
        return false;
    }    
    /**
     * @inheritdoc
     */
    public function hasPermission($permission): bool
    {
        if(is_string($permission)){
            return in_array($permission, $this->identity->getPermissions());
        }elseif(is_array($permission)){
            foreach($permission as $p){
                if($this->hasPermission($p)){
                    return true;
                }
            }
        }
        return false;
    }
}
