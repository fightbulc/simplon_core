<?php

namespace Simplon\Core\Views;

use Simplon\Core\Data\CoreViewData;
use Simplon\Core\Interfaces\ViewInterface;
use Simplon\Device\Device;
use Simplon\Form\View\FormView;
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
     * @var CoreViewData
     */
    protected $coreViewData;
    /**
     * @var Template
     */
    protected $renderer;
    /**
     * @var ViewInterface[]
     */
    protected $implementsView = [];

    /**
     * @param CoreViewData $coreViewData
     */
    public function __construct(CoreViewData $coreViewData)
    {
        $this->coreViewData = $coreViewData;
    }

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
        return Phtml::render($path, $data);
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
     * @return CoreViewData
     */
    public function getCoreViewData(): CoreViewData
    {
        return $this->coreViewData;
    }

    /**
     * @return Locale
     */
    public function getLocale(): Locale
    {
        return $this->coreViewData->getLocale();
    }

    /**
     * @return FlashMessage
     */
    public function getFlashMessage(): FlashMessage
    {
        return $this->coreViewData->getFlashMessage();
    }

    /**
     * @return Device
     */
    public function getDevice(): Device
    {
        return $this->coreViewData->getDevice();
    }

    /**
     * @return string
     * @throws PhtmlException
     */
    public function render(): string
    {
        $localData = $this->getData();

        $localData['locale'] = $this->getLocale();
        $localData['flash'] = $this->getFlashMessage();
        $localData['device'] = $this->getDevice();

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

                $localData[$subViewId] = $subView->render();
            }
        }

        return $this->renderPartial(
            $this->getDeviceTemplate(), $localData
        );
    }

    /**
     * @param FormView $formView
     *
     * @return static
     */
    public function addFormAssets(FormView $formView)
    {
        foreach ($formView->getCssAssets() as $asset)
        {
            $this->addFormCss($asset);
        }

        foreach ($formView->getJsAssets() as $asset)
        {
            $this->addFormJs($asset);
        }

        $this->addFormCode($formView->getCodeAssets());

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
                $assets = array_merge_recursive($assets, $view->getAssetsCss());
            }
        }

        return $this->cleanAssetsFromDuplicates($assets);
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
                $assets = array_merge_recursive($assets, $view->getAssetsJs());
            }
        }

        return $this->cleanAssetsFromDuplicates($assets);
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
                $assets = array_merge_recursive($assets, $view->getAssetsCode());
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
     * @param array $assets
     *
     * @return array
     */
    protected function cleanAssetsFromDuplicates(array $assets): array
    {
        $clean = [];

        if (!empty($assets))
        {
            foreach ($assets as $block => $files)
            {
                if (empty($clean[$block]))
                {
                    $clean[$block] = [];
                }

                foreach ($files as $file)
                {
                    if (!in_array($file, $clean[$block]))
                    {
                        $clean[$block][] = $file;
                    }
                }
            }
        }

        return $clean;
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
     *
     * @return string
     * @throws PhtmlException
     */
    protected function renderPartial(string $path, array $data = []): string
    {
        return $this->getRenderer()->renderPhtml($path, $data, true);
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
     * @param string $code
     *
     * @return View
     */
    protected function addFormCss(string $code): self
    {
        $this->getRenderer()->addAssetCss($code, 'form');

        return $this;
    }

    /**
     * @param string $code
     *
     * @return View
     */
    protected function addFormJs(string $code): self
    {
        $this->getRenderer()->addAssetJs($code, 'form');

        return $this;
    }

    /**
     * @param string $code
     *
     * @return View
     */
    protected function addFormCode(string $code): self
    {
        $this->getRenderer()->addAssetCode($code, 'form');

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

        //
        // tablet can fallback to mobile if available
        //

        if ($this->getDevice()->isTypeTablet())
        {
            $testFilePath = $baseFilePath . ucfirst(strtolower(Device::TYPE_MOBILE)) . '.' . $fileExtension;

            if (file_exists($testFilePath))
            {
                return $testFilePath;
            }
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