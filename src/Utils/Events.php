<?php

namespace Core\Utils;

use App\AppContext;
use Core\Interfaces\EventsInterface;

/**
 * Class Events
 * @package Core\Utils
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