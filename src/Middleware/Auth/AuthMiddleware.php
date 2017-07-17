<?php

namespace Simplon\Core\Middleware\Auth;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Simplon\Core\Data\AuthRouteData;
use Simplon\Core\Interfaces\AuthContainerInterface;
use Simplon\Core\Interfaces\AuthUserInterface;
use Simplon\Core\Interfaces\RegistryInterface;

/**
 * @package Simplon\Core\Middleware\Auth
 */
class AuthMiddleware
{
    const TEMP_TOKEN_KEY = 'temp_token';

    /**
     * @var AuthContainerInterface
     */
    private $authContainer;
    /**
     * @var RegistryInterface[]
     */
    private $components;

    /**
     * @param AuthContainerInterface $authContainer
     * @param RegistryInterface[] $components
     */
    public function __construct(AuthContainerInterface $authContainer, array $components)
    {
        $this->authContainer = $authContainer;
        $this->components = $components;
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
        if ($route = $this->findAuthRoute($request))
        {
            if ($tempToken = $this->fetchTempToken($request))
            {
                if (!$this->getAuthContainer()->isValidTempToken($tempToken))
                {
                    return $this->getAuthContainer()->runOnError($response->withStatus(403));
                }
            }

            else
            {
                $user = $this->getAuthContainer()->fetchUser($request);

                if (!$user)
                {
                    return $this->getAuthContainer()->runOnError($response->withStatus(403));
                }

                if (!$this->isAllowedGroup($route, $user))
                {
                    return $this->getAuthContainer()->runOnError($response->withStatus(403));
                }

                $response = $this->getAuthContainer()->runOnSuccess($response, $user);
            }
        }

        return $response = $next($request, $response);
    }

    /**
     * @return AuthContainerInterface
     */
    private function getAuthContainer(): AuthContainerInterface
    {
        return $this->authContainer;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return null|AuthRouteData
     */
    private function findAuthRoute(ServerRequestInterface $request): ?AuthRouteData
    {
        $currentPath = $request->getUri()->getPath();

        foreach ($this->components as $component)
        {
            if ($routes = $component->getAuthRoutes())
            {
                foreach ($routes as $route)
                {
                    $quotedPattern = preg_quote($route->getPattern(), '/');
                    $quotedPattern = preg_replace('/\\\{\w+\\\}/i', '.*?', $quotedPattern);

                    // look if pattern is part of URI

                    if (preg_match('/' . $quotedPattern . '/i', $currentPath))
                    {
                        return $route;
                    }
                }
            }
        }

        return null;
    }

    /**
     * @param AuthRouteData $route
     * @param AuthUserInterface $user
     *
     * @return bool
     */
    private function isAllowedGroup(AuthRouteData $route, AuthUserInterface $user): bool
    {
        return $user->isSuperUser() || $route->inGroup($user);
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return string|null
     */
    private function fetchTempToken(ServerRequestInterface $request): ?string
    {
        $params = $request->getQueryParams();

        if ($this->getAuthContainer()->allowTempToken() && !empty($params[self::TEMP_TOKEN_KEY]))
        {
            return $params[self::TEMP_TOKEN_KEY];
        }

        return null;
    }
}