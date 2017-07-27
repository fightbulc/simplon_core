<?php

namespace Simplon\Core\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Simplon\Core\Components\ComponentsCollection;
use Simplon\Core\Interfaces\ControllerInterface;
use Simplon\Core\Interfaces\RegistryInterface;
use Simplon\Core\Interfaces\ResponseDataInterface;
use Simplon\Core\Utils\Exceptions\ClientException;
use Simplon\Core\Utils\Exceptions\ServerException;

/**
 * Class RouteMiddleware
 * @package Simplon\Core\Middleware
 */
class RouteMiddleware
{
    /**
     * @var ComponentsCollection
     */
    private $components;
    /**
     * @var null|string
     */
    private $fallbackRoute;
    /**
     * @var string
     */
    private $allowedRouteChars = '\w+-=%/';

    /**
     * @param ComponentsCollection $components
     * @param null|string $fallbackRoute
     */
    public function __construct(ComponentsCollection $components, ?string $fallbackRoute = null)
    {
        $this->components = $components;
        $this->fallbackRoute = $fallbackRoute;
    }

    /**
     * @param string $allowedRouteChars
     *
     * @return RouteMiddleware
     */
    public function setAllowedRouteChars(string $allowedRouteChars): RouteMiddleware
    {
        $this->allowedRouteChars = $allowedRouteChars;

        return $this;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable|null $next
     *
     * @return ResponseInterface
     * @throws ClientException
     * @throws ServerException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, ?callable $next = null): ResponseInterface
    {
        $requestedPath = $request->getUri()->getPath();

        foreach ($this->buildRouteMetaData() as $component)
        {
            $isMatchedRoute =
                $this->hasAllowedMethod($request, $component['methods'])
                && preg_match('|^' . $this->transformPathPlaceholders($component['path']) . '/*$|i', $requestedPath, $match);

            if ($isMatchedRoute)
            {
                $params = [];

                foreach ($this->getPathPlaceholders($component['path']) as $placeholder)
                {
                    $placeholder = str_replace('*', '', $placeholder);

                    if (!empty($match[$placeholder]))
                    {
                        $params[$placeholder] = rtrim($match[$placeholder], '/');
                    }
                }

                /** @var ControllerInterface $controller */
                $controller = new $component['controller'](
                    $component['registry'], $request, $response
                );

                /** @var ResponseDataInterface $responseData */
                /** @noinspection PhpParamsInspection */
                $responseData = call_user_func($controller, $params);
                $response = $responseData->render();

                if ($next)
                {
                    $response = $next($request, $response);
                }

                return $response;
            }
        }

        if ($this->fallbackRoute)
        {
            return $response->withAddedHeader('Location', $this->fallbackRoute);
        }

        throw (new ClientException())->contentNotFound([
            'reason'   => 'requested resource does not exist',
            'resource' => $requestedPath,
        ]);
    }

    /**
     * @return array
     * @throws ServerException
     */
    private function buildRouteMetaData(): array
    {
        $collect = [];

        foreach ($this->components->get() as $component)
        {
            if ($component instanceof RegistryInterface)
            {
                if ($component->getRoutes())
                {
                    foreach ($component->getRoutes()->getRoutes() as $route)
                    {
                        foreach ($route->getMethodsAllowed() as $method)
                        {
                            $key = $method . ' ' . $route->getPath();

                            if (isset($collect[$key]))
                            {
                                throw (new ServerException())->internalError([
                                    'component' => get_class($component),
                                    'message'   => 'Dublicate path detection',
                                    'reason'    => 'Route has already been defined',
                                    'route'     => $key,
                                ]);
                            }

                            $collect[$key] = [
                                'path'       => $route->getPath(),
                                'methods'    => $route->getMethodsAllowed(),
                                'controller' => $route->getController(),
                                'registry'   => $component,
                            ];
                        }
                    }
                }
            }
            else
            {
                throw (new ServerException())->internalError(['reason' => 'Component "' . get_class($component) . '" did not implement RegisterInterface']);
            }
        }

        return $collect;
    }

    /**
     * @param ServerRequestInterface $request
     * @param array $methodsAllowed
     *
     * @return bool
     */
    private function hasAllowedMethod(ServerRequestInterface $request, array $methodsAllowed): bool
    {
        return in_array(strtoupper($request->getMethod()), $methodsAllowed);
    }

    /**
     * @param string $path
     *
     * @return array
     */
    private function getPathPlaceholders(string $path): array
    {
        $placeholders = [];

        if (strpos($path, '{') !== false)
        {
            preg_match_all('/\{(.*?)\}/', $path, $match);

            foreach ($match[1] as $placeholder)
            {
                $placeholders[] = $placeholder;
            }
        }

        return $placeholders;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    private function transformPathPlaceholders(string $path): string
    {
        foreach ($this->getPathPlaceholders($path) as $placeholder)
        {
            $optional = null;

            if (strpos($placeholder, '*') !== false)
            {
                $optional = '*';
                $placeholder = str_replace('*', '', $placeholder);
                $path = str_replace($placeholder . '*', $placeholder, $path);
            }

            $path = str_replace('{' . $placeholder . '}', $optional . '(?<' . $placeholder . '>[' . $this->allowedRouteChars . ']+)' . $optional, $path);
        }

        return rtrim($path, '/');
    }
}