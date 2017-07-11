<?php

namespace {namespace};

use App\AppContext;
use App\Components\Controllers\{name}ViewController;
use Simplon\Core\Components\Registry;
use Simplon\Core\Data\RouteData;
use Simplon\Core\Utils\RoutesCollection;

/**
 * @package {namespace}
 */
class {name}Registry extends Registry
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
     * @return {name}Context
     */
    public function getContext(): {name}Context
    {
        return new {name}Context($this->appContext);
    }

    /**
     * @return null|RoutesCollection
     */
    public function getRoutes(): ?RoutesCollection
    {
        return (new RoutesCollection())
            ->addRouteData(new RouteData({name}Routes::PATTERN_REGISTER, {name}ViewController::class));
    }
}