<?php

namespace App\Views;

use App\Data\GlobalViewData;
use Simplon\Core\Data\CoreViewData;
use Simplon\Core\Views\View;

/**
 * @package App\Views
 */
class AppPageView extends View
{
    /**
     * @var GlobalViewData
     */
    private $globalViewData;

    /**
     * @param CoreViewData $coreViewData
     * @param GlobalViewData $globalViewData
     */
    public function __construct(CoreViewData $coreViewData, GlobalViewData $globalViewData)
    {
        parent::__construct($coreViewData);
        $this->globalViewData = $globalViewData;

        $this->addCss(AppAssets::CSS_APP);
        $this->addJs(AppAssets::JS_APP);
    }

    /**
     * @return string
     */
    protected function getTemplate(): string
    {
        return __DIR__ . '/AppPageTemplate.phtml';
    }

    /**
     * @return array
     */
    protected function getData(): array
    {
        return [];
    }
}