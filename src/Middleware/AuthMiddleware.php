<?php

namespace Simplon\Core\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Simplon\Core\Storage\SessionStorage;

/**
 * Class AuthMiddleware
 * @package Simplon\Core\Middleware
 */
class AuthMiddleware
{
    /**
     * @var SessionStorage
     */
    private $sessionStore;

    public function __construct(SessionStorage $sessionStore)
    {
        $this->sessionStore = $sessionStore;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null): ResponseInterface
    {
        if ($next)
        {
            $response = $next($request, $response);
        }

        return $response;
    }
}