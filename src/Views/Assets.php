<?php

namespace Simplon\Core\Views;

use Simplon\Template\Template;

/**
 * Class Assets
 * @package Simplon\Core\Views
 */
class Assets
{
    /**
     * @var Template
     */
    private $renderer;

    /**
     * @param Template $template
     */
    public function __construct(Template $template)
    {
        $this->renderer = $template;
    }

    /**
     * @param string $path
     *
     * @return static
     */
    public function addCssVendor($path)
    {
        return $this->addCss($path, 'vendor');
    }

    /**
     * @param string $path
     *
     * @return static
     */
    public function addCssApp($path)
    {
        return $this->addCss($path, 'app');
    }

    /**
     * @param string $path
     *
     * @return static
     */
    public function addCssComponent($path)
    {
        return $this->addCss($path, 'component');
    }

    /**
     * @param string $path
     *
     * @return static
     */
    public function addJsVendor($path)
    {
        return $this->addJs($path, 'vendor');
    }

    /**
     * @param string $path
     *
     * @return static
     */
    public function addJsApp($path)
    {
        return $this->addJs($path, 'app');
    }

    /**
     * @param string $path
     *
     * @return static
     */
    public function addJsComponent($path)
    {
        return $this->addJs($path, 'component');
    }

    /**
     * @param string $code
     *
     * @return static
     */
    public function addCode($code)
    {
        $this->renderer->addAssetCode($code);

        return $this;
    }

    /**
     * @param string $path
     * @param string $blockId
     *
     * @return static
     */
    private function addCss($path, $blockId = null)
    {
        $this->renderer->addAssetCss($path, $blockId);

        return $this;
    }

    /**
     * @param string $path
     * @param null $blockId
     *
     * @return static
     */
    private function addJs($path, $blockId = null)
    {
        $this->renderer->addAssetJs($path, $blockId);

        return $this;
    }
}