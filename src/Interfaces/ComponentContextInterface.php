<?php

namespace Simplon\Core\Interfaces;

/**
 * Interface ComponentContextInterface
 * @package Simplon\Core\Interfaces
 */
interface ComponentContextInterface
{
    /**
     * @return CoreContextInterface
     */
    public function getAppContext();

    /**
     * @param array $keys
     *
     * @return mixed
     */
    public function getConfig(array $keys = []);
}