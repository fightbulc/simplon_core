<?php

namespace Simplon\Core\Interfaces;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface AuthContainerInterface
{
    /**
     * @param ServerRequestInterface $request
     *
     * @return null|AuthUserInterface
     */
    public function fetchUser(ServerRequestInterface $request): ?AuthUserInterface;

    /**
     * @return bool
     */
    public function allowTempToken(): bool;

    /**
     * @param string $token
     *
     * @return bool
     */
    public function isValidTempToken(string $token): bool;

    /**
     * @param callable $callback
     *
     * @return AuthContainerInterface
     */
    public function onSuccess(callable $callback): AuthContainerInterface;

    /**
     * @param ResponseInterface $response
     * @param AuthUserInterface $authUser
     *
     * @return ResponseInterface
     */
    public function runOnSuccess(ResponseInterface $response, AuthUserInterface $authUser): ResponseInterface;

    /**
     * @param callable $callback
     *
     * @return AuthContainerInterface
     */
    public function onError(callable $callback): AuthContainerInterface;

    /**
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function runOnError(ResponseInterface $response): ResponseInterface;
}