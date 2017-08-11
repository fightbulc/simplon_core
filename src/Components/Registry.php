<?php

namespace Simplon\Core\Components;

use Simplon\Core\Data\AuthRoute;
use Simplon\Core\Data\Route;
use Simplon\Core\Interfaces\EventsInterface;
use Simplon\Core\Interfaces\RegistryInterface;
use Simplon\Core\Utils\Routing\AuthRoutesCollection;
use Simplon\Core\Utils\Routing\RoutesCollection;

abstract class Registry implements RegistryInterface
{
    abstract public function getContext();

    /**
     * @return null|RoutesCollection
     */
    public function getRoutes(): ?RoutesCollection
    {
        return null;
    }

    /**
     * @return null|AuthRoutesCollection
     */
    public function getAuthRoutes(): ?AuthRoutesCollection
    {
        return null;
    }

    /**
     * @return null|EventsInterface
     */
    public function getEvents(): ?EventsInterface
    {
        return null;
    }

    /**
     * @return RoutesCollection
     */
    public function buildRoutesCollection(): RoutesCollection
    {
        return new RoutesCollection();
    }

    /**
     * @param string $path
     * @param string $controller
     *
     * @return Route
     */
    public function buildRoute(string $path, string $controller): Route
    {
        return new Route($path, $controller);
    }

    /**
     * @return AuthRoutesCollection
     */
    public function buildAuthRoutesCollection(): AuthRoutesCollection
    {
        return new AuthRoutesCollection();
    }

    /**
     * @param string $path
     * @param array $roles
     *
     * @return AuthRoute
     */
    public function buildAuthRoute(string $path, array $roles = []): AuthRoute
    {
        return new AuthRoute($path, $roles);
    }
}