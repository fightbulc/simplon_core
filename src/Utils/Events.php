<?php

namespace Simplon\Core\Utils;

use App\AppContext;
use Simplon\Core\Interfaces\EventsInterface;

/**
 * Class Events
 * @package Simplon\Core\Utils
 */
abstract class Events implements EventsInterface
{
    /**
     * @var AppContext
     */
    private $appContext;

    /**
     * @inheritDoc
     */
    public function __construct(AppContext $appContext)
    {
        $this->appContext = $appContext;
    }
}