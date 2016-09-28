<?php

namespace Simplon\Core\Controllers;

use Simplon\Core\Data\ResponseViewData;
use Simplon\Core\Interfaces\AppContextInterface;
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
            'locale' => $this->getLocale(LocaleMiddleware::getLocaleCode()),
            'flash'  => $this->getFlashMessage(),
        ]);

        $this->getResponse()->getBody()->write($view->render($globalData));

        return new ResponseViewData($this->getResponse());
    }

    /**
     * @param string $code
     *
     * @return Locale
     */
    public function getLocale(string $code): Locale
    {
        if (!$this->locale)
        {
            $paths = array_merge(AppContextInterface::getLocalePaths(), [$this->getWorkingDir() . '/Locales']);
            $this->locale = new Locale(AppContextInterface::getLocaleFileReader($paths), [$code]);
            $this->locale->setLocale($code);
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
            $this->flashMessage = new FlashMessage($this->getAppContext()->getSessionStorage());
        }

        return $this->flashMessage;
    }
}