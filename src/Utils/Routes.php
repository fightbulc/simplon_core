<?php

namespace Simplon\Core\Utils;

use Simplon\Core\Middleware\LocaleMiddleware;
use Simplon\Url\Url;

/**
 * Class Routes
 * @package Simplon\Core\Utils
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
        return LocaleMiddleware::getLocaleCode();
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