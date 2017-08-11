<?php

namespace Simplon\Core\Data;

use Fig\Http\Message\RequestMethodInterface;

class Route implements RequestMethodInterface
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
    protected $methodsAllowed = [];

    /**
     * @param string $path
     * @param string $controller
     */
    public function __construct(string $path, string $controller)
    {
        $this->path = $path;
        $this->controller = $controller;
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
     * @return Route
     */
    public function withGet(): self
    {
        return $this->addMethodAllowed(RequestMethodInterface::METHOD_GET);
    }

    /**
     * @return Route
     */
    public function withPost(): self
    {
        return $this->addMethodAllowed(RequestMethodInterface::METHOD_POST);
    }

    /**
     * @return Route
     */
    public function withPut(): self
    {
        return $this->addMethodAllowed(RequestMethodInterface::METHOD_PUT);
    }

    /**
     * @return Route
     */
    public function withPatch(): self
    {
        return $this->addMethodAllowed(RequestMethodInterface::METHOD_PATCH);
    }

    /**
     * @return Route
     */
    public function withDelete(): self
    {
        return $this->addMethodAllowed(RequestMethodInterface::METHOD_DELETE);
    }

    /**
     * @return Route
     */
    public function withOption(): self
    {
        return $this->addMethodAllowed(RequestMethodInterface::METHOD_OPTIONS);
    }

    /**
     * @return Route
     */
    public function withHead(): self
    {
        return $this->addMethodAllowed(RequestMethodInterface::METHOD_HEAD);
    }

    /**
     * @param string $method
     *
     * @return Route
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