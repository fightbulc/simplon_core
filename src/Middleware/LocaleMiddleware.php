<?php

namespace Simplon\Core\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class LocaleMiddleware
 * @package Simplon\Core\Middleware
 */
class LocaleMiddleware
{
    const HEADER_ORIGINAL_REQUEST_PATH = 'X-Original-Request-Path';

    /**
     * @var string
     */
    private static $localeCode;

    /**
     * @var array
     */
    private $locales;

    /**
     * @return string
     */
    public static function getLocaleCode(): string
    {
        return self::$localeCode;
    }

    /**
     * @param array $locales
     */
    public function __construct(array $locales)
    {
        $this->locales = $locales;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null): ResponseInterface
    {
        if (preg_match('/\/(\w{2})\/*/', $request->getUri()->getPath(), $match))
        {
            if (in_array($match[1], $this->locales))
            {
                self::$localeCode = $match[1];
                $request = $this->removeLocaleFromRequest($request, self::getLocaleCode());
            }
        }

        if (self::$localeCode === null)
        {
            $url = $request->getUri()->getScheme() . '://' . $request->getUri()->getHost() . '/' . $this->locales[0] . '/' . trim($request->getUri()->getPath(), '/');

            return $response->withStatus(302)->withHeader('Location', $url);
        }

        if ($next)
        {
            $response = $next($request, $response);
        }

        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @param string $locale
     *
     * @return ServerRequestInterface
     */
    private function removeLocaleFromRequest(ServerRequestInterface $request, string $locale): ServerRequestInterface
    {
        return $request
            ->withAddedHeader(self::HEADER_ORIGINAL_REQUEST_PATH, $request->getUri()->getPath())
            ->withUri(
                $request->getUri()->withPath(
                    str_replace('/' . $locale, '', $request->getUri()->getPath())
                )
            );
    }
}