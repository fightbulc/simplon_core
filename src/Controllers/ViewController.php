<?php

namespace Simplon\Core\Controllers;

use Simplon\Core\CoreContext;
use Simplon\Core\Data\ResponseViewData;
use Simplon\Core\Interfaces\ViewInterface;
use Simplon\Core\Middleware\LocaleMiddleware;
use Simplon\Core\Views\FlashMessage;
use Simplon\Locale\Locale;

/**
 * Class ViewController
 * @package Simplon\Core\Controllers
 */
abstract class ViewController extends Controller
{
    /**
     * @var Locale
     */
    protected $locale;
    /**
     * @var FlashMessage
     */
    protected $flashMessage;

    /**
     * @param array $params
     *
     * @return ResponseViewData
     */
    abstract public function __invoke(array $params): ResponseViewData;

    /**
     * @param ViewInterface $view
     * @param array $globalData
     *
     * @return ResponseViewData
     */
    public function respond(ViewInterface $view, array $globalData = []): ResponseViewData
    {
        $globalData = array_merge($globalData, [
            'locale' => $this->getLocale(),
            'flash'  => $this->getFlashMessage(),
        ]);

        $this->getResponse()->getBody()->write($view->render($globalData));

        return new ResponseViewData($this->getResponse());
    }

    /**
     * @param string $url
     *
     * @return ResponseViewData
     */
    public function redirect(string $url): ResponseViewData
    {
        return new ResponseViewData(
            $this->getResponse()->withHeader('Location', $url)
        );
    }

    /**
     * @return Locale
     */
    public function getLocale(): Locale
    {
        if (!$this->locale)
        {
            $paths = [
                CoreContext::APP_PATH . '/Locales', // app path
                $this->getWorkingDir() . '/Locales', // component path
            ];

            $this->locale = new Locale(CoreContext::getLocaleFileReader($paths), [LocaleMiddleware::getLocaleCode()]);
            $this->locale->setLocale(LocaleMiddleware::getLocaleCode());
        }

        return $this->locale;
    }

    /**
     * @return FlashMessage
     */
    public function getFlashMessage(): FlashMessage
    {
        if (!$this->flashMessage)
        {
            $this->flashMessage = new FlashMessage(CoreContext::getSessionStorage());
        }

        return $this->flashMessage;
    }
}