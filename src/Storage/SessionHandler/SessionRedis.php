<?php

namespace Simplon\Core\Storage\SessionHandler;

use Simplon\Core\Interfaces\SessionHandlerInterface;

/**
 * Class SessionRedis
 * @package Simplon\Core\Storage\SessionHandler
 */
class SessionRedis implements SessionHandlerInterface
{
    /**
     * @var string
     */
    private $host;
    /**
     * @var string
     */
    private $port;

    /**
     * @param string $host
     * @param string $port
     */
    public function __construct(string $host, string $port)
    {
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * @return string
     */
    public function getSavePath(): string
    {
        return 'tcp://' . $this->host . ':' . $this->port;
    }

    /**
     * @return string
     */
    public function getSaveHandler(): string
    {
        return 'redis';
    }
}