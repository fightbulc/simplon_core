<?php

namespace Simplon\Core\Interfaces;

/**
 * Interface SessionStorageInterface
 * @package Simplon\Core\Interfaces
 */
interface SessionStorageInterface
{
    /**
     * @param string $key
     * @param $data
     *
     * @return bool
     */
    public function set(string $key, $data): bool;

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * @param string $key
     *
     * @return bool
     */
    public function del(string $key): bool;

    /**
     * @return bool
     */
    public function destroy(): bool;
}