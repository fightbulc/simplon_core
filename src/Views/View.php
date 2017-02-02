<?php

namespace Simplon\Core\Views;

use Simplon\Core\Interfaces\ViewInterface;
use Simplon\Device\Device;
use Simplon\Locale\Locale;
use Simplon\Phtml\Phtml;
use Simplon\Phtml\PhtmlException;
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
     * @var Locale
     */
    protected $locale;
    /**
     * @var FlashMessage
     */
    protected $flashMessage;
    /**
     * @var Device
     */
    protected $device;

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
     * @throws PhtmlException
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
     * @return Locale
     */
    public function getLocale(): Locale
    {
        return $this->locale;
    }

    /**
     * @param Locale $locale
     *
     * @return static
     */
    public function setLocale(Locale $locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return FlashMessage
     */
    public function getFlashMessage(): FlashMessage
    {
        return $this->flashMessage;
    }

    /**
     * @param FlashMessage $flashMessage
     *
     * @return static
     */
    public function setFlashMessage(FlashMessage $flashMessage)
    {
        $this->flashMessage = $flashMessage;

        return $this;
    }

    /**
     * @return Device
     */
    public function getDevice(): Device
    {
        return $this->device;
    }

    /**
     * @param Device $device
     *
     * @return static
     */
    public function setDevice(Device $device)
    {
        $this->device = $device;

        return $this;
    }

    /**
     * @param array $globalData
     *
     * @return string
     * @throws PhtmlException
     */
    public function render(array $globalData = []): string
    {
        $data = $this->getData();
        $data['locale'] = $this->getLocale();
        $data['flash'] = $this->getFlashMessage();
        $data['device'] = $this->getDevice();

        if (!empty($this->implementsView))
        {
            foreach ($this->implementsView as $subViewId => $subView)
            {
                $this
                    ->getRenderer()
                    ->addMultipleAssetsCss($subView->getAssetsCss())
                    ->addMultipleAssetsJs($subView->getAssetsJs())
                    ->addMultipleAssetsCode($subView->getAssetsCode())
                ;

                $data[$subViewId] = $subView
                    ->setLocale($this->getLocale())
                    ->setFlashMessage($this->getFlashMessage())
                    ->setDevice($this->getDevice())
                    ->render($globalData)
                ;
            }
        }

        return $this->renderPartial($this->getDeviceTemplate(), $data, array_replace_recursive($this->getGlobalData(), $globalData));
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
     * @return ViewInterface
     */
    public function implements (ViewInterface $view, string $id = 'partial'): ViewInterface
    {
        $this->implementsView[$id] = $view;

        return $this;
    }

    /**
     * @param ViewInterface $view
     *
     * @return ViewInterface
     */
    protected function initializeNewView(ViewInterface $view): ViewInterface
    {
        return $view
            ->setLocale($this->getLocale())
            ->setFlashMessage($this->getFlashMessage())
            ->setDevice($this->getDevice())
            ;
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
     * @throws PhtmlException
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
     * @return string
     */
    protected function getDeviceTemplate(): string
    {
        $filePathPartials = explode('.', $this->getTemplate());
        $fileExtension = array_pop($filePathPartials);
        $baseFilePath = implode('.', $filePathPartials);

        $testFilePath = $baseFilePath . ucfirst(strtolower($this->getDevice()->getType())) . '.' . $fileExtension;

        if (file_exists($testFilePath))
        {
            return $testFilePath;
        }

        return $this->getTemplate();
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