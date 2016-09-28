<?php

namespace Simplon\Core\Interfaces;

/**
 * Interface EventsInterface
 * @package Simplon\Core\Interfaces
 */
interface EventsInterface
{
    /**
     * @param AppContextInterface $appContext
     */
    public function __construct(AppContextInterface $appContext);

    /**
     * @return array
     */
    public function getSubscriptions(): array;

    /**
     * @return array
     */
    public function getOffers(): array;
}