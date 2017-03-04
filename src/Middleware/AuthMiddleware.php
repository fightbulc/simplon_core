<?php

namespace Simplon\Core\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Simplon\Core\Data\AuthRouteData;
use Simplon\Core\Interfaces\AuthUserInterface;
use Simplon\Core\Utils\AuthConfig;

/**
 * Class AuthMiddleware
 * @package Simplon\Core\Middleware
 */
class AuthMiddleware
{
    /**
     * @var AuthConfig
     */
    private $authConfig;

    /**
     * @param AuthConfig $authConfig
     */
    public function __construct(AuthConfig $authConfig)
    {
        $this->authConfig = $authConfig;
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
        $path = $request->getUri()->getPath();

        /** @var AuthUserInterface $user */
        $user = $this->authConfig->getSessionStorage()->get('AUTH:USER');

        if (!$user && !$this->isPublicRoute($path))
        {
            $deniedRoute = $this->authConfig->getDeniedAccessRoute();

            if ($user && !$this->isAllowedGroup($path, $user))
            {
                $deniedRoute = $this->authConfig->getDeniedWrongGroupRoute();
            }

            return $response->withHeader('Location', $deniedRoute);
        }

        return $response = $next($request, $response);
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    private function isPublicRoute(string $path): bool
    {
        return $this->isAllowedRoute($path, function (AuthRouteData $route)
        {
            return $route->hasGroups() === false;
        });
    }

    /**
     * @param string $path
     * @param AuthUserInterface $user
     *
     * @return bool
     */
    private function isAllowedGroup(string $path, AuthUserInterface $user): bool
    {
        return $this->isAllowedRoute($path, function (AuthRouteData $route) use ($user)
        {
            return $route->inGroup($user);
        });
    }

    /**
     * @param string $path
     * @param callable $callback
     *
     * @return bool
     */
    private function isAllowedRoute(string $path, callable $callback): bool
    {
        $isAllowed = false;

        if ($this->authConfig->getRoutes())
        {
            foreach ($this->authConfig->getRoutes() as $route)
            {
                $quotedPattern = preg_quote($route->getPattern(), '/');
                $quotedPattern = preg_replace('/\\\{\w+\\\}/i', '.*?', $quotedPattern);

                if (preg_match('/' . $quotedPattern . '/i', $path))
                {
                    $isAllowed = $callback($route);
                }
            }
        }

        return $isAllowed;
    }
}