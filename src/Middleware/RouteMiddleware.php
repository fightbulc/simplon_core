<?php

namespace Simplon\Core\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Simplon\Core\Interfaces\ControllerInterface;
use Simplon\Core\Interfaces\CoreContextInterface;
use Simplon\Core\Interfaces\RegisterInterface;
use Simplon\Core\Interfaces\ResponseDataInterface;

/**
 * Class RouteMiddleware
 * @package Simplon\Core\Middleware
 */
class RouteMiddleware
{
    /**
     * @var CoreContextInterface
     */
    private $coreContext;
    /**
     * @var RegisterInterface[]
     */
    private $components;

    /**
     * @param CoreContextInterface $coreContext
     * @param RegisterInterface[] $components
     */
    public function __construct(CoreContextInterface $coreContext, array $components)
    {
        $this->coreContext = $coreContext;
        $this->components = $components;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable|null $next
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null): ResponseInterface
    {
        $requestedPath = $request->getUri()->getPath();

        foreach ($this->collectRoutes() as $path => $component)
        {
            $isMatchedRoute =
                $this->hasAllowedMethod($request, $component['methods'])
                && preg_match('|^' . $this->transformPathPlaceholders($path) . '/*$|i', $requestedPath, $match);

            if ($isMatchedRoute)
            {
                $params = [];

                foreach ($this->getPathPlaceholders($path) as $placeholder)
                {
                    if (isset($match[$placeholder]))
                    {
                        $params[$placeholder] = rtrim($match[$placeholder], '/');
                    }
                }

                /** @var ControllerInterface $controller */
                $controller = new $component['controller'];

                $controller
                    ->setRequest($request)
                    ->setResponse($response)
                    ->setContext($component['context'])
                    ->setWorkingDir($component['workingDir']);

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

        throw new \Exception("could not match any component routes to <{$requestedPath}>");
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function collectRoutes(): array
    {
        $collect = [];

        foreach ($this->components as $component)
        {
            if ($component instanceof RegisterInterface)
            {
                foreach ($component->getRoutes()->getRouteData() as $routeData)
                {
                    if (isset($collect[$routeData->getPath()]))
                    {
                        throw new \Exception("Path is already taken by {$collect[$routeData->getPath()]}");
                    }

                    $collect[$routeData->getPath()] = [
                        'methods'    => $routeData->getMethodsAllowed(),
                        'controller' => $routeData->getController(),
                        'workingDir' => $component->getWorkingDir(),
                        'context'    => $component->getContext(),
                    ];

                    // register component events
                    $this->registerEvents($component);
                }
            }
            else
            {
                throw new \Exception('Component "' . get_class($component) . '" did not implement RegisterInterface');
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
            $path = str_replace('{' . $placeholder . '}', '(?<' . $placeholder . '>[\w+-/]+)', $path);
        }

        return rtrim($path, '/');
    }

    /**
     * @param RegisterInterface $register
     *
     * @return RouteMiddleware
     */
    private function registerEvents(RegisterInterface $register): self
    {
        if ($register->getEvents())
        {
            // add subscriptions

            if (empty($register->getEvents()->getSubscriptions()) === false)
            {
                foreach ($register->getEvents()->getSubscriptions() as $event => $callback)
                {
                    $this->coreContext->getEventsHandler()->addSubscription($event, $callback);
                }
            }

            // add offers

            if (empty($register->getEvents()->getOffers()) === false)
            {
                foreach ($register->getEvents()->getOffers() as $event => $callback)
                {
                    $this->coreContext->getEventsHandler()->addOffer($event, $callback);
                }
            }
        }

        return $this;
    }
}