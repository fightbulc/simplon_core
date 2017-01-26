<?php

namespace Simplon\Core\Utils\Exceptions;

/**
 * Class ClientException
 * @package Simplon\Core\Utils\Exceptions
 */
class ClientException extends ErrorException
{
    const STATUS_BAD_REQUEST = 400;
    const STATUS_UNAUTHORIZED = 401;
    const STATUS_FORBIDDEN = 403;
    const STATUS_NOT_FOUND = 404;
    const STATUS_METHOD_NOT_ALLOWED = 405;
    const STATUS_CONTENT_CONFLICT = 409;
    const STATUS_CONTENT_HAS_BEEN_DELETED = 410;
    const STATUS_INVALID_DATA = 422;
    const STATUS_TOO_MANY_REQUESTS = 429;

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
            ->setHttpStatusCode(self::STATUS_BAD_REQUEST)
            ->setMessage('Server could not understand the request due to invalid syntax.')
            ->setPublicData($data);
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function requestUnauthorized(array $data = [])
    {
        return $this
            ->setHttpStatusCode(self::STATUS_UNAUTHORIZED)
            ->setMessage('Authentication is needed to access requested content.')
            ->setPublicData($data);
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function requestForbidden(array $data = [])
    {
        return $this
            ->setHttpStatusCode(self::STATUS_FORBIDDEN)
            ->setMessage('Nobody is allowed to access this content.')
            ->setPublicData($data);
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function contentNotFound(array $data = [])
    {
        return $this
            ->setHttpStatusCode(self::STATUS_NOT_FOUND)
            ->setMessage('Cannot find your requested content.')
            ->setPublicData($data);
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function requestMethodNotAllowed(array $data = [])
    {
        return $this
            ->setHttpStatusCode(self::STATUS_METHOD_NOT_ALLOWED)
            ->setMessage('Type of request method is not allowed.')
            ->setPublicData($data);
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function contentConflict(array $data = [])
    {
        return $this
            ->setHttpStatusCode(self::STATUS_CONTENT_CONFLICT)
            ->setMessage('The content you are trying to update has changed. Please refresh.')
            ->setPublicData($data);
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function contentHasBeenDeleted(array $data = [])
    {
        return $this
            ->setHttpStatusCode(self::STATUS_CONTENT_HAS_BEEN_DELETED)
            ->setMessage('The requested content has been deleted.')
            ->setPublicData($data);
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function requestHasInvalidData(array $data = [])
    {
        return $this
            ->setHttpStatusCode(self::STATUS_INVALID_DATA)
            ->setMessage('Your request data are not valid.')
            ->setPublicData($data);
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function tooManyRequests(array $data = [])
    {
        return $this
            ->setHttpStatusCode(self::STATUS_TOO_MANY_REQUESTS)
            ->setMessage('You had too many requests. Please wait for a while and try again later.')
            ->setPublicData($data);
    }
}