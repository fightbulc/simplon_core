<?php

namespace Simplon\Core\Views;

use Simplon\Interfaces\StorageInterface;

class FlashMessage
{
    const SESSION_KEY = 'FLASHMESSAGE';
    const TYPE_INFO = 'info';
    const TYPE_SUCCESS = 'success';
    const TYPE_WARNING = 'warning';
    const TYPE_ERROR = 'error';

    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @return bool
     */
    public function hasFlash(): bool
    {
        return $this->getStorage()->has(self::SESSION_KEY);
    }

    /**
     * @param string $size
     *
     * @return null|string
     */
    public function getFlash(string $size = 'large')
    {
        $validSizes = [
            'mini', 'tiny', 'small', 'large', 'big', 'huge', 'massive',
        ];

        if (!in_array($size, $validSizes))
        {
            $size = 'large';
        }

        // fetch message
        $flash = $this->getStorage()->get(self::SESSION_KEY);

        // remove from session
        $this->getStorage()->del(self::SESSION_KEY);

        if ($flash === null)
        {
            return null;
        }

        return '<div class="ui ' . $size . ' ' . $flash['type'] . ' message flash-message">' . $flash['message'] . '</div>';
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
        $this->getStorage()->set(self::SESSION_KEY, ['message' => $message, 'type' => $type]);

        return $this;
    }

    /**
     * @return StorageInterface
     */
    private function getStorage(): StorageInterface
    {
        return $this->storage;
    }
}