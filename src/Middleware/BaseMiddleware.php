<?php

namespace Simplon\Core\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Simplon\Core\Interfaces\MiddlewareInterface;

/**
 * @package Simplon\Core\Middleware
 */
abstract class BaseMiddleware implements MiddlewareInterface
{
    const HEADER_ORIGINAL_REQUEST_PATH = 'X-Original-Request-Path';

    /**
     * @param ServerRequestInterface $request
     *
     * @return null|string
     */
    public static function getOriginalRequestPath(ServerRequestInterface $request): ?string
    {
        $value = $request->getHeader(self::HEADER_ORIGINAL_REQUEST_PATH);

        if (!empty($value))
        {
            return array_pop($value);
        }

        return null;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return bool
     */
    public static function hasOriginalRequestPath(ServerRequestInterface $request): bool
    {
        return self::getOriginalRequestPath($request) !== null;
    }

    /**
     * @param ServerRequestInterface $request
     * @param string $uriPart
     *
     * @return ServerRequestInterface
     */
    protected function removeFromUri(ServerRequestInterface $request, string $uriPart): ServerRequestInterface
    {
        if (self::hasOriginalRequestPath($request) === false)
        {
            $request = $request->withAddedHeader(self::HEADER_ORIGINAL_REQUEST_PATH, $request->getUri()->getPath());
        }

        return $request->withUri(
            $request->getUri()->withPath(str_replace($uriPart, '', $request->getUri()->getPath()))
        );
    }
}