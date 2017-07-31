<?php

namespace Simplon\Core\Utils\Routing;

use Simplon\Url\Url;

/**
 * @package Simplon\Core\Utils\Routing
 */
abstract class Routes
{
    /**
     * @param string $route
     * @param array $params
     *
     * @return Url
     */
    public static function render(string $route, array $params = []): Url
    {
        $url = new Url();

        if ($prefix = static::routePrefix())
        {
            $url->withPath($prefix);
        }

        $url->withTrailPath($route, $params);

        // remove unset optional params

        $url->withPath(
            preg_replace('/\{.*?\*\}/i', '', $url->getPath())
        );

        if ($host = self::routeHost())
        {
            $url->withHost($host);
        }

        return $url;
    }

    /**
     * @param string $route
     * @param array $params
     * @param Url $url
     *
     * @return Url
     */
    public static function renderCustom(string $route, array $params = [], Url $url): Url
    {
        return $url->withTrailPath($route, $params);
    }

    /**
     * @return null|string
     */
    protected static function routePrefix(): ?string
    {
        return null;
    }

    /**
     * @return null|string
     */
    protected static function routeHost(): ?string
    {
        return null;
    }

    /**
     * @param Url $url
     *
     * @return string
     */
    protected static function toString(Url $url): string
    {
        return $url->__toString();
    }
}