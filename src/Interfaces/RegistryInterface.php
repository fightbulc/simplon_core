<?php

namespace Simplon\Core\Interfaces;

use Simplon\Core\Utils\Routing\AuthRoutesCollection;
use Simplon\Core\Utils\Routing\RoutesCollection;

/**
 * @package Simplon\Core\Interfaces
 */
interface RegistryInterface
{
    public function getContext();

    /**
     * @return null|RoutesCollection
     */
    public function getRoutes(): ?RoutesCollection;

    /**
     * @return null|AuthRoutesCollection
     */
    public function getAuthRoutes(): ?AuthRoutesCollection;

    /**
     * @return null|EventsInterface
     */
    public function getEvents(): ?EventsInterface;
}