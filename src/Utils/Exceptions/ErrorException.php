<?php

namespace Simplon\Core\Utils\Exceptions;

/**
 * Class ErrorException
 * @package Simplon\Error
 */
abstract class ErrorException extends \Exception
{
    /**
     * @var int
     */
    protected $httpStatusCode;
    /**
     * @var array
     */
    protected $publicData = [];

    /**
     * @param \Throwable|null $previous
     */
    public function __construct(\Throwable $previous = null)
    {
        parent::__construct(null, null, $previous);
    }

    /**
     * @return int
     */
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }

    /**
     * @param string $message
     *
     * @return static
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function getPublicData($key = null)
    {
        if ($key)
        {
            if (empty($this->publicData[$key]) === false)
            {
                return $this->publicData[$key];
            }

            return null;
        }

        return $this->publicData;
    }

    /**
     * @param array $publicData
     *
     * @return static
     */
    protected function setPublicData(array $publicData)
    {
        $this->publicData = $publicData;

        return $this;
    }

    /**
     * @param int $code
     *
     * @return static
     */
    protected function setHttpStatusCode($code)
    {
        $this->httpStatusCode = $code;

        return $this;
    }
}