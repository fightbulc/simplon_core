<?php

namespace Simplon\Core\Utils;

use Simplon\Core\Data\RouteData;

/**
 * Class RoutesCollection
 * @package Simplon\Core\Utils
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