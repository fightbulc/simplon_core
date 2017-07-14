<?php

namespace Simplon\Core\Middleware\Auth;

use Psr\Http\Message\ResponseInterface;
use Simplon\Core\Interfaces\AuthUserInterface;

/**
 * @package Simplon\Core\Middleware\Auth
 */
abstract class AuthContainer implements AuthContainerInterface
{
    /**
     * @var AuthUserInterface|null
     */
    protected static $authenticatedUser;
    /**
     * @var callable
     */
    protected $onSuccess;
    /**
     * @var callable
     */
    protected $onError;

    /**
     * @return null|AuthUserInterface
     */
    public static function getAuthenticatedUser()
    {
        return self::$authenticatedUser;
    }

    /**
     * @param AuthUserInterface $authUser
     */
    public static function setAuthenticatedUser(AuthUserInterface $authUser): void
    {
        self::$authenticatedUser = $authUser;
    }

    /**
     * @return bool
     */
    public function allowTempToken(): bool
    {
        return false;
    }

    /**
     * @param string $token
     *
     * @return bool
     */
    public function isValidTempToken(string $token): bool
    {
        return false;
    }

    /**
     * @param callable $callback
     *
     * @return AuthContainerInterface
     */
    public function onSuccess(callable $callback): AuthContainerInterface
    {
        $this->onSuccess = $callback;

        return $this;
    }

    /**
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function runOnSuccess(ResponseInterface $response): ResponseInterface
    {
        return call_user_func($this->onError, $response);
    }

    /**
     * @param callable $callback
     *
     * @return AuthContainerInterface
     */
    public function onError(callable $callback): AuthContainerInterface
    {
        $this->onError = $callback;

        return $this;
    }

    /**
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function runOnError(ResponseInterface $response): ResponseInterface
    {
        return call_user_func($this->onError, $response);
    }
}