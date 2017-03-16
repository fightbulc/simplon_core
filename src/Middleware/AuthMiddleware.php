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
     * @param callable|null $next
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, ?callable $next = null): ResponseInterface
    {
        /** @var AuthUserInterface $user */
        $user = $this->authConfig->getSessionStorage()->get(AuthConfig::SESSION_KEY);
        $path = $request->getUri()->getPath();
        $route = $this->findAuthRoute($path);

        if ($route && !$user)
        {
            $deniedRoute = $this->authConfig->getDeniedAccessRoute();

            if ($user && !$this->isAllowedGroup($route, $user))
            {
                $deniedRoute = $route->getDeniedRoute() ?? $this->authConfig->getDeniedAccessRoute();
            }

            return $response->withHeader('Location', $deniedRoute);
        }

        return $response = $next($request, $response);
    }

    /**
     * @param null|AuthRouteData $route
     * @param AuthUserInterface $user
     *
     * @return bool
     */
    private function isAllowedGroup(?AuthRouteData $route, AuthUserInterface $user): bool
    {
        return $route && $route->inGroup($user);
    }

    /**
     * @param string $path
     *
     * @return AuthRouteData
     */
    private function findAuthRoute(string $path): ?AuthRouteData
    {
        if ($this->authConfig->getRoutes())
        {
            foreach ($this->authConfig->getRoutes() as $route)
            {
                $quotedPattern = preg_quote($route->getPattern(), '/');
                $quotedPattern = preg_replace('/\\\{\w+\\\}/i', '.*?', $quotedPattern);

                if (preg_match('/^' . $quotedPattern . '$/i', $path))
                {
                    return $route;
                }
            }
        }

        return null;
    }
}