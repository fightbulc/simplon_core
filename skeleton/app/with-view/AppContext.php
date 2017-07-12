<?php

namespace App;

use App\Data\GlobalViewData;
use App\Views\AppPageView;
use Simplon\Core\CoreContext;
use Simplon\Core\Data\CoreViewData;

/**
 * @package App
 */
class AppContext extends CoreContext
{
    public function getAppPageView(CoreViewData $coreViewData, GlobalViewData $globalViewData): AppPageView
    {
        return new AppPageView($coreViewData, $globalViewData);
    }

    public function getGlobaViewData(): GlobalViewData
    {
        return new GlobalViewData();
    }

    /**
     * @return string
     */
    protected function getCookieStorageNameSpace(): string
    {
        return '{name}';
    }
}