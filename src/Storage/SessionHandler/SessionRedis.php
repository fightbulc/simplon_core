<?php

namespace Storage\SessionHandler;

use Interfaces\SessionHandlerInterface;

/**
 * Class SessionRedis
 * @package Storage\SessionHandler
 */
class SessionRedis implements SessionHandlerInterface
{
    /**
     * @var string
     */
    private $host;
    /**
     * @var int
     */
    private $port;

    /**
     * @param string $host
     * @param int $port
     */
    public function __construct(string $host, int $port)
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