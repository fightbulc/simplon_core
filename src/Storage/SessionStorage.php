<?php

namespace Simplon\Core\Storage;

use Simplon\Core\Interfaces\SessionHandlerInterface;
use Simplon\Core\Interfaces\SessionStorageInterface;

/**
 * Class SessionStorage
 * @package Simplon\Core\Storage
 */
class SessionStorage implements SessionStorageInterface
{
    /**
     * @param int $sessionTimeoutSeconds
     * @param SessionHandlerInterface|null $handler
     */
    public static function initSession(int $sessionTimeoutSeconds, SessionHandlerInterface $handler = null)
    {
        if (empty(session_id()))
        {
            // set handler
            if ($handler)
            {
                ini_set("session.save_handler", $handler->getSaveHandler());
                ini_set("session.save_path", $handler->getSavePath());
            }

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
     * @param mixed $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        if (isset($_SESSION[$key]) === false)
        {
            return $default;
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