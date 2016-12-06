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
     * @var ViewInterface[]
     */
    protected $implementsView = [];
    /**
     * @var array
     */
    protected $globalData = [];

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
     * @throws \Simplon\Phtml\PhtmlException
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
     * @param array $globalData
     *
     * @return string
     */
    public function render(array $globalData = []): string
    {
        $data = $this->getData();

        if (!empty($this->implementsView))
        {
            foreach ($this->implementsView as $id => $view)
            {
                $this->getRenderer()
                    ->addMultipleAssetsCss($view->getAssetsCss())
                    ->addMultipleAssetsJs($view->getAssetsJs())
                    ->addMultipleAssetsCode($view->getAssetsCode());

                $data[$id] = $view->render($globalData);
            }
        }

        return $this->renderPartial($this->getTemplate(), $data, array_merge($this->getGlobalData(), $globalData));
    }

    /**
     * @return array
     */
    public function getGlobalData(): array
    {
        return $this->globalData;
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return View
     */
    public function addGlobalData(string $key, $value): View
    {
        $this->globalData[$key] = $value;

        return $this;
    }

    /**
     * @param array $globalData
     *
     * @return View
     */
    public function setGlobalData(array $globalData): View
    {
        $this->globalData = $globalData;

        return $this;
    }

    /**
     * @return array
     */
    public function getAssetsCss(): array
    {
        $assets = $this->getRenderer()->getAssetsCss();

        if (!empty($this->implementsView))
        {
            foreach ($this->implementsView as $id => $view)
            {
                $assets = array_merge($assets, $view->getAssetsCss());
            }
        }

        return $assets;
    }

    /**
     * @return array
     */
    public function getAssetsJs(): array
    {
        $assets = $this->getRenderer()->getAssetsJs();

        if (!empty($this->implementsView))
        {
            foreach ($this->implementsView as $id => $view)
            {
                $assets = array_merge($assets, $view->getAssetsJs());
            }
        }

        return $assets;
    }

    /**
     * @return array
     */
    public function getAssetsCode(): array
    {
        $assets = $this->getRenderer()->getAssetsCode();

        if (!empty($this->implementsView))
        {
            foreach ($this->implementsView as $id => $view)
            {
                $assets = array_merge($assets, $view->getAssetsCode());
            }
        }

        return $assets;
    }

    /**
     * @param ViewInterface $view
     * @param string $id
     *
     * @return View
     */
    public function implements (ViewInterface $view, string $id = 'partial'): View
    {
        $this->implementsView[$id] = $view;

        return $this;
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
     * @return string
     */
    abstract protected function getTemplate(): string;

    /**
     * @return array
     */
    protected function getData(): array
    {
        return [];
    }

    /**
     * @param string $path
     * @param array $data
     * @param array $globalData
     *
     * @return string
     */
    protected function renderPartial(string $path, array $data = [], array $globalData = []): string
    {
        return $this->getRenderer()->renderPhtml($path, array_merge($data, $globalData), true);
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
    protected function addCss(string $path, string $blockId = null): self
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
    protected function addJs(string $path, string $blockId = null): self
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