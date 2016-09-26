<?php

namespace Core\Utils;

use Core\Data\RouteData;

/**
 * Class RoutesCollection
 * @package Core\Utils
 */
class RoutesCollection
{
    /**
     * @var RouteData[]
     */
    protected $routeData = [];

    /**
     * @return RouteData[]
     */
    public function getRouteData(): array
    {
        return $this->routeData;
    }

    /**
     * @param RouteData $data
     *
     * @return RoutesCollection
     */
    public function addRouteData(RouteData $data): self
    {
        $this->routeData[] = $data;

        return $this;
    }
}