<?php

namespace Simplon\Core\Utils;

use Simplon\Core\Middleware\LocaleMiddleware;

/**
 * Class Routes
 * @package Simplon\Core\Utils
 */
abstract class Routes
{
    /**
     * @param string $route
     * @param array $params
     * @param bool $withPrefix
     *
     * @return string
     */
    public static function render(string $route, array $params = [], bool $withPrefix = true): string
    {
        $parts = [$route];

        if ($withPrefix)
        {
            array_unshift($parts, static::routePrefix());
        }

        return static::routeUrl() . static::replacePlaceholders(static::trimUrl($parts), $params);
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
    protected static function routeUrl(): ?string
    {
        return '/';
    }

    /**
     * @param array $parts
     *
     * @return string
     */
    protected static function trimUrl(array $parts): string
    {
        return preg_replace('/\/\//', '/', implode('/', $parts));
    }

    /**
     * @param string $url
     * @param array $params
     *
     * @return string
     */
    protected static function replacePlaceholders(string $url, array $params = []): string
    {
        foreach ($params as $key => $val)
        {
            $url = str_replace('{' . $key . '}', $val, $url);
        }

        return $url;
    }
}