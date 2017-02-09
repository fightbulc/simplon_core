<?php

namespace Simplon\Core\Utils\Exceptions;

use Fig\Http\Message\StatusCodeInterface;

/**
 * Class ServerException
 * @package Simplon\Core\Utils\Exceptions
 */
class ServerException extends ErrorException
{
    /**
     * @param array $data
     *
     * @return $this
     */
    public function internalError(array $data = [])
    {
        return $this
            ->setHttpStatusCode(StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR)
            ->setMessage('We encountered an unexpected issue. We are on it')
            ->setPublicData($data)
            ;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function invalidResponseGateway(array $data = [])
    {
        return $this
            ->setHttpStatusCode(StatusCodeInterface::STATUS_BAD_GATEWAY)
            ->setMessage('A gateway server/service responded with an error')
            ->setPublicData($data)
            ;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function timeoutGateway(array $data = [])
    {
        return $this
            ->setHttpStatusCode(StatusCodeInterface::STATUS_GATEWAY_TIMEOUT)
            ->setMessage('The requested gateway server/service timed out')
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
            ->setHttpStatusCode(StatusCodeInterface::STATUS_SERVICE_UNAVAILABLE)
            ->setMessage('We are currently not available. Check back in a short time')
            ->setPublicData($data)
            ;
    }
}