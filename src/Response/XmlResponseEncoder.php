<?php

namespace Simplon\Core\Response;

use Spatie\ArrayToXml\ArrayToXml;

class XmlResponseEncoder implements ResponseEncoder
{
    /**
     * @var array
     */
    private $data;
    /**
     * @var string
     */
    private $root;

    /**
     * @param array $data
     * @param string $root
     */
    public function __construct(array $data, string $root = 'root')
    {
        $this->data = $data;
        $this->root = $root;
    }

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return 'application/xml; charset=UTF-8';
    }

    /**
     * @return string
     */
    public function encodeData(): string
    {
        return ArrayToXml::convert($this->data, $this->root);
    }
}