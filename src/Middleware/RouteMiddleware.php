<?php

namespace Simplon\Core\Middleware;

use Simplon\Core\Interfaces\AppContextInterface;
use Simplon\Core\Interfaces\ControllerInterface;
use Simplon\Core\Interfaces\RegisterInterface;
use Simplon\Core\Interfaces\ResponseDataInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class RouteMiddleware
 * @package Simplon\Core\Middleware
 */
class RouteMiddleware
{
    /**
     * @var RegisterInterface[]
     */
    private $components;
    /**
     * @var AppContextInterface
     */
    private $appContext;

    /**
     * @param AppContextInterface $appContext
     * @param RegisterInterface[] $components
     */
    public function __construct(AppContextInterface $appContext, array $components)
    {
        $this->components = $components;
        $this->appContext = $appContext;
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
                        $params[$placeholder] = $match[$placeholder];
                    }
                }

                /** @var ControllerInterface $controller */
                $controller = new $component['controller'];

                $controller
                    ->setAppContext($this->appContext)
                    ->setRequest($request)
                    ->setResponse($response)
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
            $component->setAppContext($this->appContext);

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
                ];

                // register component events
                $this->registerEvents($component);
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

        return trim($path, '/');
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
                    $this->appContext->getEventsHandler()->addSubscription($event, $callback);
                }
            }

            // add offers

            if (empty($register->getEvents()->getOffers()) === false)
            {
                foreach ($register->getEvents()->getOffers() as $event => $callback)
                {
                    $this->appContext->getEventsHandler()->addOffer($event, $callback);
                }
            }
        }

        return $this;
    }
}