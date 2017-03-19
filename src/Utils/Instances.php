<?php

namespace Simplon\Core\Utils;

/**
 * @package Simplon\Core\Utils
 */
class Instances
{
    /**
     * @var array
     */
    private static $cache = [];

    /**
     * @param string $classNamespace
     * @param array $params
     * @param callable|null $optional
     *
     * @return mixed
     */
    public static function cache(string $classNamespace, array $params = [], ?callable $optional = null)
    {
        if (empty(self::$cache[$classNamespace]))
        {
            self::$cache[$classNamespace] = new $classNamespace(...$params);

            if ($optional)
            {
                self::$cache[$classNamespace] = $optional(self::$cache[$classNamespace]);
            }
        }

        return self::$cache[$classNamespace];
    }

}