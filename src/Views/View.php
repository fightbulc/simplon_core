<?php

namespace Simplon\Core\Views;

use Simplon\Core\Interfaces\ViewInterface;
use Simplon\Phtml\Phtml;
use Simplon\Template\Template;

/**
 * Class View
 * @package Simplon\Core\Views
 */
abstract class View implements ViewInterface
{
    /**
     * @var Template
     */
    protected $renderer;

    /**
     * @param string $string
     * @param string|null $functions
     *
     * @return string
     */
    public static function escape(string $string, string $functions = null): string
    {
        static $flags;

        if (!isset($flags))
        {
            $flags = ENT_QUOTES | (defined('ENT_SUBSTITUTE') ? ENT_SUBSTITUTE : 0);
        }

        if ($functions)
        {
            $string = self::batch($string, $functions);
        }

        return htmlspecialchars($string, $flags, 'UTF-8');
    }

    /**
     * @param string $path
     * @param array $data
     *
     * @return string
     */
    public static function renderWidget(string $path, array $data = []): string
    {
        return Phtml::render($path, $data, '');
    }

    /**
     * @param string $text
     * @param array $placeholder
     *
     * @return string
     */
    public static function renderPlaceholder(string $text, array $placeholder): string
    {
        foreach ($placeholder as $key => $val)
        {
            $text = str_replace('{' . $key . '}', $val, $text);
        }

        return $text;
    }

    /**
     * @param string $var
     * @param string $functions
     *
     * @return string
     * @throws \LogicException
     */
    protected static function batch(string $var, string $functions): string
    {
        foreach (explode('|', $functions) as $function)
        {
            if (function_exists($function))
            {
                $var = call_user_func($function, $var);
            }
            elseif (is_callable($function))
            {
                $var = call_user_func($function, $var);
            }
            else
            {
                throw new \LogicException('Escape could not find the "' . $function . '" function.');
            }
        }

        return $var;
    }

    /**
     * @param PageView $view
     *
     * @return string
     */
    protected function renderPage(PageView $view): string
    {
        foreach ($view->getPartials() as $partial)
        {
            $view->addGlobalData([
                $partial->getId() => $this->renderPartial($partial, $view->getGlobalData()),
            ]);
        }

        return $this->getRenderer()->renderPhtml(
            $view->getPagePath(), $view->getGlobalData(), true
        );
    }

    /**
     * @param PartialView $view
     * @param array $globalData
     *
     * @return string
     */
    protected function renderPartial(PartialView $view, array $globalData): string
    {
        return $this->getRenderer()->renderPhtml(
            $view->getPath(), array_merge($globalData, $view->getData()), true
        );
    }

    /**
     * @param string $path
     *
     * @return View
     */
    protected function addCssVendor(string $path): self
    {
        return $this->addCss($path, 'vendor');
    }

    /**
     * @param string $path
     *
     * @return View
     */
    protected function addCssApp(string $path): self
    {
        return $this->addCss($path, 'app');
    }

    /**
     * @param string $path
     *
     * @return View
     */
    protected function addCssComponent(string $path): self
    {
        return $this->addCss($path, 'component');
    }

    /**
     * @param string $path
     *
     * @return View
     */
    protected function addJsVendor(string $path): self
    {
        return $this->addJs($path, 'vendor');
    }

    /**
     * @param string $path
     *
     * @return View
     */
    protected function addJsApp(string $path): self
    {
        return $this->addJs($path, 'app');
    }

    /**
     * @param string $path
     *
     * @return View
     */
    protected function addJsComponent(string $path): self
    {
        return $this->addJs($path, 'component');
    }

    /**
     * @param string $code
     *
     * @return View
     */
    protected function addHeaderCode(string $code): self
    {
        $this->getRenderer()->addAssetCode($code, 'header');

        return $this;
    }

    /**
     * @param string $code
     *
     * @return View
     */
    protected function addBodyStartCode(string $code): self
    {
        $this->getRenderer()->addAssetCode($code, 'bodyStart');

        return $this;
    }

    /**
     * @param string $code
     *
     * @return View
     */
    protected function addBodyEndCode(string $code): self
    {
        $this->getRenderer()->addAssetCode($code, 'bodyEnd');

        return $this;
    }

    /**
     * @deprecated
     *
     * @param string $code
     *
     * @return View
     */
    protected function addFooterCode(string $code): self
    {
        $this->getRenderer()->addAssetCode($code, 'footer');

        return $this;
    }

    /**
     * @param string $path
     * @param string|null $blockId
     *
     * @return View
     */
    private function addCss(string $path, string $blockId = null): self
    {
        $this->getRenderer()->addAssetCss($path, $blockId);

        return $this;
    }

    /**
     * @param string $path
     * @param string|null $blockId
     *
     * @return View
     */
    private function addJs(string $path, string $blockId = null): self
    {
        $this->getRenderer()->addAssetJs($path, $blockId);

        return $this;
    }

    /**
     * @return Template
     */
    private function getRenderer(): Template
    {
        if (!$this->renderer)
        {
            $this->renderer = new Template();
        }

        return $this->renderer;
    }
}