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
     * @var array
     */
    private $enabledModules;

    /**
     * @param array $enabledModules
     */
    public function __construct(array $enabledModules = [])
    {
        $this->enabledModules = $enabledModules;
    }

    /**
     * @return array
     */
    public function getEnabledModules(): array
    {
        return $this->enabledModules;
    }

    /**
     * @param string $module
     *
     * @return bool
     */
    public function isEnabledModule(string $module): bool
    {
        return in_array($module, $this->enabledModules);
    }

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