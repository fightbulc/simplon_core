<?php

namespace Simplon\Core\Utils\Exceptions;

/**
 * Class ServerException
 * @package Simplon\Core\Utils\Exceptions
 */
class ServerException extends ErrorException
{
    const STATUS_INTERNAL_ERROR = 500;
    const STATUS_INVALID_RESPONSE_UPSTREAM = 502;
    const STATUS_SERVICE_UNAVAILABLE = 503;
    const STATUS_TIMEOUT_UPSTREAM = 504;

    /**
     * @param array $data
     *
     * @return $this
     */
    public function internalError(array $data = [])
    {
        return $this
            ->setHttpStatusCode(self::STATUS_INTERNAL_ERROR)
            ->setMessage('We encountered an unexpected issue. We are on it.')
            ->setPublicData($data)
            ;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function invalidResponseUpstream(array $data = [])
    {
        return $this
            ->setHttpStatusCode(self::STATUS_INVALID_RESPONSE_UPSTREAM)
            ->setMessage('An upstream server/service responded with an error.')
            ->setPublicData($data)
            ;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function serviceUnavailable(array $data = [])
    {
        return $this
            ->setHttpStatusCode(self::STATUS_SERVICE_UNAVAILABLE)
            ->setMessage('We are currently not available. Check back in a short time.')
            ->setPublicData($data)
            ;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function timeoutUpstream(array $data = [])
    {
        return $this
            ->setHttpStatusCode(self::STATUS_TIMEOUT_UPSTREAM)
            ->setMessage('The requested upstream server/service timed out.')
            ->setPublicData($data)
            ;
    }
}