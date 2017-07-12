<?php

namespace {namespace}\Controllers;

use App\AppContext;
use {namespace}\{name}Context;
use {namespace}\{name}Registry;
use Simplon\Core\Controllers\RestController;

/**
 * @package App\Components\Auth\Controllers
 */
abstract class BaseRestController extends RestController
{
    /**
     * @return {name}Registry
     */
    public function getRegistry(): {name}Registry
    {
        return $this->registry;
    }

    /**
     * @return {name}Context
     */
    protected function getContext(): {name}Context
    {
        return $this->getRegistry()->getContext();
    }

    /**
     * @return AppContext
     */
    protected function getAppContext(): AppContext
    {
        return $this->getContext()->getAppContext();
    }
}