<?php

namespace Core\Controllers;

use App\AppContext;
use Core\Data\ResponseViewData;
use Core\Interfaces\ViewInterface;
use Core\Middleware\LocaleMiddleware;
use Core\Views\FlashMessage;
use Simplon\Locale\Locale;

/**
 * Class ViewController
 * @package Core\Controllers
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
            $paths = array_merge(AppContext::getLocalePaths(), [$this->getWorkingDir() . '/Locales']);
            $this->locale = new Locale(AppContext::getLocaleFileReader($paths), [$code]);
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