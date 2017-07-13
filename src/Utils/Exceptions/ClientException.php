<?php

namespace Simplon\Core\Utils\Exceptions;

use Fig\Http\Message\StatusCodeInterface;

/**
 * Class ClientException
 * @package Simplon\Core\Utils\Exceptions
 */
class ClientException extends ErrorException
{
    /**
     * @var array
     */
    protected $privateData = [];

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function getPrivateData($key = null)
    {
        if ($key)
        {
            if (empty($this->privateData[$key]) === false)
            {
                return $this->privateData[$key];
            }

            return null;
        }

        return $this->privateData;
    }

    /**
     * @param array $privateData
     *
     * @return $this
     */
    public function setPrivateData(array $privateData)
    {
        $this->privateData = $privateData;

        return $this;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function cannotUnderstandRequest(array $data = [])
    {
        return $this
            ->setHttpStatusCode(StatusCodeInterface::STATUS_BAD_REQUEST)
            ->setMessage('Server could not understand the request due to invalid syntax')
            ->setPublicData($data)
            ;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function requestUnauthorized(array $data = [])
    {
        return $this
            ->setHttpStatusCode(StatusCodeInterface::STATUS_UNAUTHORIZED)
            ->setMessage('Authentication required for requested content')
            ->setPublicData($data)
            ;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function paymentRequired(array $data = [])
    {
        return $this
            ->setHttpStatusCode(StatusCodeInterface::STATUS_PAYMENT_REQUIRED)
            ->setMessage('Payment required to continue')
            ->setPublicData($data)
            ;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function requestForbidden(array $data = [])
    {
        return $this
            ->setHttpStatusCode(StatusCodeInterface::STATUS_FORBIDDEN)
            ->setMessage('Access denied for requested content')
            ->setPublicData($data)
            ;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function contentNotFound(array $data = [])
    {
        return $this
            ->setHttpStatusCode(StatusCodeInterface::STATUS_NOT_FOUND)
            ->setMessage('Cannot find your requested content')
            ->setPublicData($data)
            ;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function requestMethodNotAllowed(array $data = [])
    {
        return $this
            ->setHttpStatusCode(StatusCodeInterface::STATUS_METHOD_NOT_ALLOWED)
            ->setMessage('Type of request method is not allowed')
            ->setPublicData($data)
            ;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function contentConflict(array $data = [])
    {
        return $this
            ->setHttpStatusCode(StatusCodeInterface::STATUS_CONFLICT)
            ->setMessage('The content you are trying to update has changed. Please refresh')
            ->setPublicData($data)
            ;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function contentHasBeenDeleted(array $data = [])
    {
        return $this
            ->setHttpStatusCode(StatusCodeInterface::STATUS_GONE)
            ->setMessage('The requested content has been deleted')
            ->setPublicData($data)
            ;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function requestHasInvalidData(array $data = [])
    {
        return $this
            ->setHttpStatusCode(StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY)
            ->setMessage('Your request data are not valid')
            ->setPublicData($data)
            ;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function tooManyRequests(array $data = [])
    {
        return $this
            ->setHttpStatusCode(StatusCodeInterface::STATUS_TOO_MANY_REQUESTS)
            ->setMessage('You had too many requests. Please wait for a while and try again later')
            ->setPublicData($data)
            ;
    }
}