<?php

namespace Simplon\Core\Utils;

use Simplon\Core\Data\AuthRouteData;
use Simplon\Core\Interfaces\AuthUserInterface;
use Simplon\Core\Interfaces\RegisterInterface;
use Simplon\Core\Interfaces\SessionStorageInterface;

/**
 * @package Simplon\Core\Utils
 */
class AuthConfig
{
    const SESSION_KEY = 'AUTH:USER';
    const TOKEN_KEY = 'auth_token';
    const TOKEN_TTL_SECS = 1 * 60 * 60; // 1 hour

    /**
     * @var SessionStorageInterface
     */
    private $sessionStorage;
    /**
     * @var string
     */
    private $token;
    /**
     * @var AuthRouteData[]
     */
    private $routes = [];
    /**
     * @var callable|null
     */
    private $callbackVerifyToken;
    /**
     * @var string
     */
    private $deniedAccessRoute;
    /**
     * @var AuthUserInterface
     */
    private $authUserShell;

    /**
     * @param SessionStorageInterface $sessionStorage
     * @param AuthUserInterface $authUserShell
     * @param string $deniedAccessRoute
     */
    public function __construct(SessionStorageInterface $sessionStorage, AuthUserInterface $authUserShell, string $deniedAccessRoute)
    {
        $this->sessionStorage = $sessionStorage;
        $this->deniedAccessRoute = $deniedAccessRoute;
        $this->authUserShell = $authUserShell;
    }

    /**
     * @return AuthUserInterface
     */
    public function getAuthUserShell(): AuthUserInterface
    {
        return $this->authUserShell;
    }

    /**
     * @return callable|null
     */
    public function getCallbackVerifyToken(): ?callable
    {
        return $this->callbackVerifyToken;
    }

    /**
     * @param callable $callbackVerifyToken
     *
     * @return AuthConfig
     */
    public function setCallbackVerifyToken(callable $callbackVerifyToken)
    {
        $this->callbackVerifyToken = $callbackVerifyToken;

        return $this;
    }

    /**
     * @return SessionStorageInterface
     */
    public function getSessionStorage(): SessionStorageInterface
    {
        return $this->sessionStorage;
    }

    /**
     * @return string
     */
    public function getDeniedAccessRoute(): string
    {
        return $this->deniedAccessRoute;
    }

    /**
     * @return AuthRouteData[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * @param AuthRouteData $data
     *
     * @return AuthConfig
     */
    public function addRoute(AuthRouteData $data): AuthConfig
    {
        $this->routes[] = $data;

        return $this;
    }

    /**
     * @param AuthRouteData[] $routes
     *
     * @return AuthConfig
     */
    public function setRoutes(array $routes): AuthConfig
    {
        $this->routes = $routes;

        return $this;
    }

    /**
     * @param RegisterInterface[] $components
     *
     * @return AuthConfig
     */
    public function loadRoutesFromComponents(array $components): AuthConfig
    {
        foreach ($components as $component)
        {
            if ($routes = $component->getAuthRoutes())
            {
                foreach ($routes as $route)
                {
                    $this->routes[] = $route;
                }
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isTokenAllowed(): bool
    {
        return $this->callbackVerifyToken !== null;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return bool
     */
    public function hasToken(): bool
    {
        return !empty($this->token);
    }

    /**
     * @param string|null $token
     *
     * @return AuthConfig
     */
    public function setToken(?string $token = null): AuthConfig
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return bool
     */
    public function verifyToken(): bool
    {
        if ($this->callbackVerifyToken && $this->hasToken())
        {
            return call_user_func($this->callbackVerifyToken, $this->getToken());
        }

        return true;
    }
}