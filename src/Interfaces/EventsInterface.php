<?php

namespace Simplon\Core\Interfaces;

use App\AppContext;

/**
 * Interface EventsInterface
 * @package Simplon\Core\Interfaces
 */
interface EventsInterface
{
    /**
     * @param AppContext $appContext
     */
    public function __construct(AppContext $appContext);

    /**
     * @return array
     */
    public function getSubscriptions(): array;

    /**
     * @return array
     */
    public function getOffers(): array;
}