<?php

namespace Simplon\Core\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Simplon\Core\Interfaces\ControllerInterface;
use Simplon\Core\Interfaces\RegistryInterface;
use Simplon\Core\Interfaces\ResponseDataInterface;
use Simplon\Core\Utils\EventsHandler;
use Simplon\Core\Utils\Exceptions\ClientException;
use Simplon\Core\Utils\Exceptions\ServerException;
use Simplon\Helper\Data\InstanceData;
use Simplon\Helper\Instances;

/**
 * Class RouteMiddleware
 * @package Simplon\Core\Middleware
 */
class RouteMiddleware
{
    /**
     * @var RegistryInterface[]
     */
    private $components;
    /**
     * @var string
     */
    private $allowedRouteChars;

    /**
     * @param array $components
     * @param string $allowedRouteChars
     */
    public function __construct(array $components, string $allowedRouteChars = '\w+-=%/')
    {
        $this->components = $components;
        $this->allowedRouteChars = $allowedRouteChars;
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
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null): ResponseInterface
    {
        $requestedPath = $request->getUri()->getPath();

        foreach ($this->collectRoutes() as $component)
        {
            $isMatchedRoute =
                $this->hasAllowedMethod($request, $component['methods'])
                && preg_match('|^' . $this->transformPathPlaceholders($component['path']) . '/*$|i', $requestedPath, $match);

            if ($isMatchedRoute)
            {
                $params = [];

                foreach ($this->getPathPlaceholders($component['path']) as $placeholder)
                {
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

        throw (new ClientException())->contentNotFound([
            'reason'   => 'requested resource does not exist',
            'resource' => $requestedPath,
        ]);
    }

    /**
     * @return array
     * @throws ServerException
     */
    private function collectRoutes(): array
    {
        $collect = [];

        foreach ($this->components as $component)
        {
            if ($component instanceof RegistryInterface)
            {
                if ($component->getRoutes())
                {
                    foreach ($component->getRoutes()->getRouteData() as $routeData)
                    {
                        foreach ($routeData->getMethodsAllowed() as $method)
                        {
                            $key = $method . ' ' . $routeData->getPath();

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
                                'path'       => $routeData->getPath(),
                                'methods'    => $routeData->getMethodsAllowed(),
                                'controller' => $routeData->getController(),
                                'registry'   => $component,
                            ];
                        }

                        // register component events
                        $this->registerEvents($component);
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
            $path = str_replace('{' . $placeholder . '}', '(?<' . $placeholder . '>[' . $this->allowedRouteChars . ']+)', $path);
        }

        return rtrim($path, '/');
    }

    /**
     * @param RegistryInterface $register
     *
     * @return RouteMiddleware
     */
    private function registerEvents(RegistryInterface $register): self
    {
        if ($register->getEvents())
        {
            // add subscriptions

            if (empty($register->getEvents()->getSubscriptions()) === false)
            {
                foreach ($register->getEvents()->getSubscriptions() as $event => $callback)
                {
                    $this->getEventsHandler()->addSubscription($event, $callback);
                }
            }

            // add offers

            if (empty($register->getEvents()->getOffers()) === false)
            {
                foreach ($register->getEvents()->getOffers() as $event => $callback)
                {
                    $this->getEventsHandler()->addOffer($event, $callback);
                }
            }
        }

        return $this;
    }

    /**
     * @return EventsHandler
     */
    private function getEventsHandler(): EventsHandler
    {
        return Instances::cache(
            InstanceData::create(EventsHandler::class)
        );
    }
}