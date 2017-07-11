<?php

namespace App\Components\Auth\Controllers;

use App\AppContext;
use App\Components\Auth\AuthContext;
use App\Components\Auth\AuthRegistry;
use Simplon\Core\Controllers\RestController;

/**
 * @package App\Components\Auth\Controllers
 */
abstract class BaseRestController extends RestController
{
    /**
     * @return AuthRegistry
     */
    public function getRegistry(): AuthRegistry
    {
        return $this->registry;
    }

    /**
     * @return AuthContext
     */
    protected function getContext(): AuthContext
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

    /**
     * @return array|null
     */
    protected function getPayload(): ?array
    {
        return json_decode($this->getRequest()->getBody()->getContents(), true);
    }
}