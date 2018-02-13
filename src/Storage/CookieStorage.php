<?php

namespace Simplon\Core\Storage;

use Simplon\Url\Url;

class CookieStorage
{
    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return empty($_COOKIE[$key]) === false;
    }

    /**
     * @param string      $key
     * @param string|null $fallback
     *
     * @return string|null
     */
    public function get(string $key, $fallback = null): ?string
    {
        if ($this->has($key))
        {
            return $_COOKIE[$key];
        }

        return $fallback;
    }

    /**
     * @param string   $key
     * @param string   $val
     * @param int|null $expiresAt
     *
     * @return CookieStorage
     */
    public function set(string $key, string $val, int $expiresAt = null): self
    {
        if ($expiresAt === null)
        {
            $expiresAt = time() + 60 * 60 * 24 * 30; // 30 days
        }

        setcookie($key, $val, $expiresAt, '/', '.' . $this->getHost());
        $_COOKIE[$key] = $val;

        return $this;
    }

    /**
     * @param string $key
     */
    public function del(string $key)
    {
        unset($_COOKIE[$key]);
        setcookie($key, null, -1, '/', '.' . $this->getHost());
    }

    /**
     * @return string
     */
    private function getHost(): string
    {
        return (new Url(Url::getCurrentUrl()))->withoutSubDomain()->getHost();
    }
}