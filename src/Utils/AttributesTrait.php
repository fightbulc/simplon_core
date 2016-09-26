<?php

namespace Core\Utils;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Class AttributesTrait
 * @package Core\Utils
 */
trait AttributesTrait
{
    /**
     * @param ServerRequestInterface $request
     *
     * @return array
     */
    private static function getPool(ServerRequestInterface $request)
    {
        return $request->getAttribute('middleware', []);
    }

    /**
     * @param ServerRequestInterface $request
     * @param string $name
     * @param mixed $value
     *
     * @return ServerRequestInterface
     */
    private static function setAttribute(ServerRequestInterface $request, $name, $value)
    {
        $attributes = self::getPool($request);
        $attributes[$name] = $value;

        return $request->withAttribute('middleware', $attributes);
    }

    /**
     * @param ServerRequestInterface $request
     * @param string $name
     *
     * @return mixed
     */
    private static function getAttribute(ServerRequestInterface $request, $name)
    {
        $attributes = self::getPool($request);

        if (isset($attributes[$name]))
        {
            return $attributes[$name];
        }
    }

    /**
     * Check whether an attribute exists.
     *
     * @param ServerRequestInterface $request
     * @param string $name
     *
     * @return bool
     */
    private static function hasAttribute(ServerRequestInterface $request, $name)
    {
        $attributes = self::getPool($request);

        if (empty($attributes))
        {
            return false;
        }

        return isset($attributes[$name]);
    }
}