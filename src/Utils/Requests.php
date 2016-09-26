<?php

namespace Core\Utils;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Requests
 * @package Core\Utils
 */
abstract class Requests
{
    /**
     * @param string $url
     * @param array $options
     *
     * @return ResponseInterface
     */
    public function get(string $url, array $options = []): ResponseInterface
    {
        return $this->getClient()->get($url, $options);
    }

    /**
     * @param string $url
     * @param array $params
     * @param array $options
     *
     * @return ResponseInterface
     */
    public function post(string $url, array $params = [], array $options = []): ResponseInterface
    {
        if (!empty($params))
        {
            $options['form_params'] = $params;
        }

        return $this->getClient()->post($url, $options);
    }

    /**
     * @param string $url
     * @param array $params
     * @param array $options
     *
     * @return ResponseInterface
     */
    public function put(string $url, array $params = [], array $options = []): ResponseInterface
    {
        if (!empty($params))
        {
            $options['form_params'] = $params;
        }

        return $this->getClient()->put($url, $options);
    }

    /**
     * @param string $url
     * @param array $params
     * @param array $options
     *
     * @return ResponseInterface
     */
    public function delete(string $url, array $params = [], array $options = []): ResponseInterface
    {
        if (!empty($params))
        {
            $options['form_params'] = $params;
        }

        return $this->getClient()->delete($url, $options);
    }

    /**
     * @return Client
     */
    private function getClient()
    {
        return new Client();
    }
}