<?php

namespace Simplon\Core\Interfaces;

/**
 * Interface EventsInterface
 * @package Simplon\Core\Interfaces
 */
interface EventsInterface
{
    /**
     * @return array
     */
    public function getSubscriptions(): array;

    /**
     * @return array
     */
    public function getOffers(): array;
}