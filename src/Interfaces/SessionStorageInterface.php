<?php

namespace Core\Interfaces;

/**
 * Interface SessionStorageInterface
 * @package Core\Interfaces
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
     *
     * @return mixed|null
     */
    public function get(string $key);

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