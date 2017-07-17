<?php

namespace Simplon\Core\Interfaces;

use Psr\Http\Message\ResponseInterface;

/**
 * @package Simplon\Core\Interfaces
 */
interface AuthContainerInterface
{
    /**
     * @return null|AuthUserInterface
     */
    public static function getAuthenticatedUser();

    /**
     * @return bool
     */
    public static function hasAuthenticatedUser(): bool;

    /**
     * @param AuthUserInterface $authUser
     */
    public static function setAuthenticatedUser(AuthUserInterface $authUser): void;

    /**
     * @param null|string $bearer
     *
     * @return null|AuthUserInterface
     */
    public function fetchUser(?string $bearer = null): ?AuthUserInterface;

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
     *
     * @return ResponseInterface
     */
    public function runOnSuccess(ResponseInterface $response): ResponseInterface;

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