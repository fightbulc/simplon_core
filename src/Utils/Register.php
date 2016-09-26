<?php

namespace Core\Utils;

use App\AppContext;
use Core\Interfaces\RegisterInterface;

/**
 * Class Register
 * @package Core\Utils
 */
abstract class Register implements RegisterInterface
{
    /**
     * @var AppContext
     */
    protected $appContext;

    /**
     * @inheritDoc
     */
    public function getAppContext(): AppContext
    {
        return $this->appContext;
    }

    /**
     * @inheritDoc
     */
    public function setAppContext(AppContext $appContext): RegisterInterface
    {
        $this->appContext = $appContext;

        return $this;
    }

}