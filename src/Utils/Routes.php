<?php

namespace Core\Utils;

use Core\Middleware\LocaleMiddleware;

/**
 * Class Routes
 * @package Core\Utils
 */
abstract class Routes
{
    /**
     * @param string $route
     * @param array $params
     *
     * @return string
     */
    public static function render(string $route, array $params = []): string
    {
        return '/' . LocaleMiddleware::getLocaleCode() . '/' . trim(self::renderUrl($route, $params), '/');
    }

    /**
     * @param string $url
     * @param array $params
     *
     * @return string
     */
    public static function renderUrl(string $url, array $params = []): string
    {
        foreach ($params as $key => $val)
        {
            $url = str_replace('{' . $key . '}', $val, $url);
        }

        return $url;
    }
}