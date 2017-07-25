<?php

namespace Simplon\Core\Middleware\Auth;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Simplon\Core\Interfaces\AuthContainerInterface;
use Simplon\Core\Interfaces\AuthUserInterface;

/**
 * @package Simplon\Core\Middleware\Auth
 */
abstract class AuthContainer implements AuthContainerInterface
{
    /**
     * @var callable
     */
    protected $onSuccess;
    /**
     * @var callable
     */
    protected $onError;

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
     * @param AuthUserInterface $authUser
     *
     * @return ResponseInterface
     */
    public function runOnSuccess(ResponseInterface $response, AuthUserInterface $authUser): ResponseInterface
    {
        $onSuccess = $this->getOnSuccess();

        if (!$onSuccess)
        {
            $onSuccess = function (ResponseInterface $response) {
                return $response;
            };
        }

        return call_user_func($onSuccess, $response, $authUser);
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
        $onError = $this->getOnError();

        if (!$onError)
        {
            $onError = function (ResponseInterface $response) {
                return $response;
            };
        }

        return call_user_func($onError, $response);
    }

    /**
     * @return callable|null
     */
    protected function getOnSuccess(): ?callable
    {
        return $this->onSuccess;
    }

    /**
     * @return callable|null
     */
    protected function getOnError(): ?callable
    {
        return $this->onError;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return null|string
     */
    protected function fetchAuthBearer(ServerRequestInterface $request): ?string
    {
        $value = $request->getHeader('Authorization');

        if (!empty($value) && preg_match('/^bearer:/i', $value[0]))
        {
            return preg_replace('/^bearer:\s*/i', '', $value[0]);
        }

        return null;
    }
}