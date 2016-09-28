<?php

namespace Simplon\Core\Utils;

use Simplon\Core\Interfaces\AppContextInterface;
use Simplon\Core\Interfaces\RegisterInterface;

/**
 * Class Register
 * @package Simplon\Core\Utils
 */
abstract class Register implements RegisterInterface
{
    /**
     * @var AppContextInterface
     */
    protected $appContext;

    /**
     * @inheritDoc
     */
    public function getAppContext(): AppContextInterface
    {
        return $this->appContext;
    }

    /**
     * @inheritDoc
     */
    public function setAppContext(AppContextInterface $appContext): RegisterInterface
    {
        $this->appContext = $appContext;

        return $this;
    }

}