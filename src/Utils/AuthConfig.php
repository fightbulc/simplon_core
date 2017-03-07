<?php

namespace Simplon\Core\Utils;

use Simplon\Core\Data\AuthRouteData;
use Simplon\Core\Interfaces\RegisterInterface;
use Simplon\Core\Interfaces\SessionStorageInterface;

/**
 * @package Simplon\Core\Utils
 */
class AuthConfig
{
    /**
     * @var SessionStorageInterface
     */
    private $sessionStorage;
    /**
     * @var bool
     */
    private $allowToken = false;
    /**
     * @var string
     */
    private $tokenName = 'auth_token';
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
     * @param SessionStorageInterface $sessionStorage
     * @param string $deniedAccessRoute
     * @param callable|null $callbackVerifyToken
     */
    public function __construct(SessionStorageInterface $sessionStorage, string $deniedAccessRoute, ?callable $callbackVerifyToken = null)
    {
        $this->sessionStorage = $sessionStorage;
        $this->deniedAccessRoute = $deniedAccessRoute;
        $this->callbackVerifyToken = $callbackVerifyToken;
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
            if ($routes = $component->getAuth())
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
        return $this->allowToken;
    }

    /**
     * @return AuthConfig
     */
    public function allowToken(): self
    {
        $this->allowToken = true;

        return $this;
    }

    /**
     * @return string
     */
    public function getTokenName(): string
    {
        return $this->tokenName;
    }

    /**
     * @param string $tokenName
     *
     * @return AuthConfig
     */
    public function setTokenName(string $tokenName): AuthConfig
    {
        $this->tokenName = $tokenName;

        return $this;
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
     * @param string $token
     *
     * @return AuthConfig
     */
    public function setToken(string $token): AuthConfig
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