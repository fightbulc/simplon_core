<?php

namespace {namespace};

use Simplon\Core\CoreContext;

/**
 * @package App
 */
class AppContext extends CoreContext
{
    /**
     * @return string
     */
    protected function getCookieStorageNameSpace(): string
    {
        return '{name}';
    }
}