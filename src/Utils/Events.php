<?php

namespace Simplon\Core\Utils;

use Simplon\Core\Interfaces\AppContextInterface;
use Simplon\Core\Interfaces\EventsInterface;

/**
 * Class Events
 * @package Simplon\Core\Utils
 */
abstract class Events implements EventsInterface
{
    /**
     * @var AppContextInterface
     */
    private $appContext;

    /**
     * @inheritDoc
     */
    public function __construct(AppContextInterface $appContext)
    {
        $this->appContext = $appContext;
    }
}