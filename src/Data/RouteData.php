<?php

namespace Simplon\Core\Data;

/**
 * Class RouteData
 * @package Simplon\Core\Data
 */
class RouteData
{
    /**
     * @var string
     */
    protected $path;
    /**
     * @var string
     */
    protected $controller;
    /**
     * @var array
     */
    protected $methodsAllowed = ['GET'];

    /**
     * @param string $path
     * @param string $controller
     * @param bool $removeDefaultGet
     */
    public function __construct(string $path, string $controller, bool $removeDefaultGet = false)
    {
        $this->path = $path;
        $this->controller = $controller;

        if ($removeDefaultGet)
        {
            $this->methodsAllowed = [];
        }
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @return array
     */
    public function getMethodsAllowed(): array
    {
        return $this->methodsAllowed;
    }

    /**
     * @return RouteData
     */
    public function withGet(): self
    {
        return $this->addMethodAllowed('GET');
    }

    /**
     * @return RouteData
     */
    public function withPost(): self
    {
        return $this->addMethodAllowed('POST');
    }

    /**
     * @return RouteData
     */
    public function withPut(): self
    {
        return $this->addMethodAllowed('PUT');
    }

    /**
     * @return RouteData
     */
    public function withDelete(): self
    {
        return $this->addMethodAllowed('DELETE');
    }

    /**
     * @param string $method
     *
     * @return RouteData
     */
    private function addMethodAllowed(string $method): self
    {
        if (!in_array($method, $this->methodsAllowed))
        {
            $this->methodsAllowed[] = $method;
        }

        return $this;
    }
}