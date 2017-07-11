<?php

namespace Simplon\Core\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class LocaleMiddleware
 * @package Simplon\Core\Middleware
 */
class LocaleMiddleware extends BaseMiddleware
{
    const DEFAULT_LOCALE_CODE = 'en';

    /**
     * @var string
     */
    private static $localeCode = self::DEFAULT_LOCALE_CODE;

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
     * @param string $code
     *
     * @return string
     */
    public static function setLocaleCode(string $code): string
    {
        return self::$localeCode = $code;
    }

    /**
     * @param array $locales
     * @param string $fallback
     */
    public function __construct(array $locales = [self::DEFAULT_LOCALE_CODE], string $fallback = self::DEFAULT_LOCALE_CODE)
    {
        $this->locales = $locales;

        //
        // make sure that the fallback is part of the locales
        //

        if (!in_array($fallback, $locales))
        {
            $fallback = $locales[0];
        }

        //
        // set default
        //

        self::$localeCode = $fallback;
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
        if (preg_match('/\/(\w{2}\-\w{2}|\w{2})\/*/', $request->getUri()->getPath(), $match))
        {
            if (in_array($match[1], $this->locales))
            {
                self::setLocaleCode($match[1]);
                $request = $this->removeFromUri($request, '/' . self::getLocaleCode());
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
}