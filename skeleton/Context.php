<?php

namespace {namespace};

use App\AppContext;
use Simplon\Core\Interfaces\ComponentContextInterface;
use Simplon\Core\Utils\Config;

/**
 * @package {namespace}
 */
class {name}Context implements ComponentContextInterface
{
    /**
     * @var AppContext
     */
    private $appContext;

    /**
     * @param AppContext $appContext
     */
    public function __construct(AppContext $appContext)
    {
        $this->appContext = $appContext;
    }

    /**
     * @return AppContext
     */
    public function getAppContext(): AppContext
    {
        return $this->appContext;
    }

    /**
     * @return null|Config
     */
    public function getConfig(): ?Config
    {
        return $this->getAppContext()->getConfig(__DIR__);
    }
}