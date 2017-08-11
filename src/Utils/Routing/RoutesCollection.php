<?php

namespace Simplon\Core\Utils\Routing;

use Simplon\Core\Data\Route;

class RoutesCollection
{
    /**
     * @var Route[]
     */
    protected $routes = [];

    /**
     * @return Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * @param Route $data
     *
     * @return RoutesCollection
     */
    public function addRoute(Route $data): self
    {
        $this->routes[] = $data;

        return $this;
    }
}