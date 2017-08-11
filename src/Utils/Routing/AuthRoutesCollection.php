<?php

namespace Simplon\Core\Utils\Routing;

use Simplon\Core\Data\AuthRoute;

class AuthRoutesCollection
{
    /**
     * @var AuthRoute[]
     */
    protected $routes = [];

    /**
     * @return AuthRoute[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * @param AuthRoute $data
     *
     * @return AuthRoutesCollection
     */
    public function addRoute(AuthRoute $data): self
    {
        $this->routes[] = $data;

        return $this;
    }
}