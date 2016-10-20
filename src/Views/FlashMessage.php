<?php

namespace Simplon\Core\Views;

use Simplon\Core\Interfaces\SessionStorageInterface;

/**
 * Class FlashMessage
 * @package Simplon\Core\Views
 */
class FlashMessage
{
    const SESSION_KEY = 'FLASHMESSAGE';
    const TYPE_INFO = 'info';
    const TYPE_SUCCESS = 'success';
    const TYPE_WARNING = 'warning';
    const TYPE_ERROR = 'error';

    /**
     * @var SessionStorageInterface
     */
    private $sessionStore;

    /**
     * @param SessionStorageInterface $sessionStorage
     */
    public function __construct(SessionStorageInterface $sessionStorage)
    {
        $this->sessionStore = $sessionStorage;
    }

    /**
     * @return bool
     */
    public function hasFlash(): bool
    {
        return $this->getSessionStore()->has(self::SESSION_KEY);
    }

    /**
     * @return null|string
     */
    public function getFlash()
    {
        // fetch message
        $flash = $this->getSessionStore()->get(self::SESSION_KEY);

        // remove from session
        $this->getSessionStore()->del(self::SESSION_KEY);

        if ($flash === null)
        {
            return null;
        }

        return '<div class="ui ' . $flash['type'] . ' message flash-message">' . $flash['message'] . '</div>';
    }

    /**
     * @param string $message
     *
     * @return FlashMessage
     */
    public function setFlashNormal(string $message): self
    {
        $this->setFlash($message);

        return $this;
    }

    /**
     * @param string $message
     *
     * @return FlashMessage
     */
    public function setFlashInfo(string $message): self
    {
        $this->setFlash($message, self::TYPE_INFO);

        return $this;
    }

    /**
     * @param string $message
     *
     * @return FlashMessage
     */
    public function setFlashSuccess(string $message): self
    {
        $this->setFlash($message, self::TYPE_SUCCESS);

        return $this;
    }

    /**
     * @param string $message
     *
     * @return FlashMessage
     */
    public function setFlashWarning(string $message): self
    {
        $this->setFlash($message, self::TYPE_WARNING);

        return $this;
    }

    /**
     * @param string $message
     *
     * @return FlashMessage
     */
    public function setFlashError(string $message): self
    {
        $this->setFlash($message, self::TYPE_ERROR);

        return $this;
    }

    /**
     * @param string $message
     * @param string|null $type
     *
     * @return FlashMessage
     */
    private function setFlash(string $message, $type = null): self
    {
        $this->getSessionStore()->set(self::SESSION_KEY, ['message' => $message, 'type' => $type]);

        return $this;
    }

    /**
     * @return SessionStorageInterface
     */
    private function getSessionStore(): SessionStorageInterface
    {
        return $this->sessionStore;
    }
}