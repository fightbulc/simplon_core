<?php

namespace Simplon\Core\Storage;

class CookieStorage
{
    /**
     * @var string
     */
    private $namespace;

    /**
     * @param string $namespace
     */
    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return empty($_COOKIE[$this->getKeyWithNamespace($key)]) === false;
    }

    /**
     * @param string $key
     * @param mixed $fallback
     *
     * @return array|null
     */
    public function get(string $key, $fallback = null): array
    {
        if ($this->has($key))
        {
            $value = $_COOKIE[$this->getKeyWithNamespace($key)];

            return json_decode($value, true);
        }

        return $fallback;
    }

    /**
     * @param string $key
     * @param array $val
     * @param int|null $expiresAt
     *
     * @return CookieStorage
     */
    public function set(string $key, array $val, int $expiresAt = null): self
    {
        if ($expiresAt === null)
        {
            $expiresAt = time() + 60 * 60 * 24 * 30; // 30 days
        }

        $key = $this->getKeyWithNamespace($key);
        $val = json_encode($val);

        setcookie($key, $val, $expiresAt, '/', '', false, true);
        $_COOKIE[$key] = $val;

        return $this;
    }

    /**
     * @param string $key
     */
    public function del(string $key)
    {
        $key = $this->getKeyWithNamespace($key);
        unset($_COOKIE[$key]);
        setcookie($key, '', time() - 3600);
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private function getKeyWithNamespace(string $key): string
    {
        return $this->namespace . ':' . $key;
    }
}