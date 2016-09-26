<?php

namespace Core\Storage;

use Core\Interfaces\SessionStorageInterface;

/**
 * Class SessionStorage
 * @package Core\Storage
 */
class SessionStorage implements SessionStorageInterface
{
    /**
     * @param int $sessionTimeoutSeconds
     */
    public static function initSession(int $sessionTimeoutSeconds = 1800)
    {
        if (empty(session_id()))
        {
            // max session lifetime
            ini_set("session.gc_maxlifetime", $sessionTimeoutSeconds);

            // max session cookie lifetime
            ini_set("session.cookie_lifetime", $sessionTimeoutSeconds);

            // start session
            session_start();

            // renew cookie lifetime
            if (isset($_COOKIE[session_name()]))
            {
                setcookie(
                    session_name(),
                    $_COOKIE[session_name()],
                    time() + $sessionTimeoutSeconds,
                    '/'
                );
            }
        }
    }

    /**
     * @param string $key
     * @param $data
     *
     * @return bool
     */
    public function set(string $key, $data): bool
    {
        $_SESSION[$key] = $data;

        if (isset($_SESSION[$key]) === false)
        {
            return false;
        }

        return true;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function get(string $key)
    {
        if (isset($_SESSION[$key]) === false)
        {
            return null;
        }

        return $_SESSION[$key];
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function del(string $key): bool
    {
        if (isset($_SESSION[$key]) === true)
        {
            unset($_SESSION[$key]);
        }

        return true;
    }

    /**
     * @return bool
     */
    public function destroy(): bool
    {
        return session_destroy();
    }
}