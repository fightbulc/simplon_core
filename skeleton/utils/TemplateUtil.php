<?php

Class TemplateUtil
{
    /**
     * @var string
     */
    private $contents;
    /**
     * @var string
     */
    private $destination;
    /**
     * @var string
     */
    private $fileName;
    /**
     * @var array
     */
    private $params = [];

    /**
     * @param string $pathContents
     *
     * @return TemplateUtil
     */
    public static function createFrom(string $pathContents): self
    {
        return new self($pathContents);
    }

    /**
     * @param string $pathContents
     */
    public function __construct(string $pathContents)
    {
        $this->contents = file_get_contents($pathContents);
    }

    /**
     * @param string $destination
     *
     * @return TemplateUtil
     */
    public function withDestination(string $destination): self
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * @param string $fileName
     *
     * @return TemplateUtil
     */
    public function withFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * @param array $params
     *
     * @return TemplateUtil
     */
    public function withParams(array $params): self
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function build(): string
    {
        if ($this->contents && $this->destination && $this->fileName)
        {
            $this->destination = rtrim($this->destination, '/');
            $this->fileName = trim($this->fileName, '/');

            foreach ($this->params as $k => $v)
            {
                $this->contents = preg_replace('/\{' . $k . '\}/iu', $v, $this->contents);
                $this->destination = str_replace('{' . $k . '}', $v, $this->destination);
                $this->fileName = str_replace('{' . $k . '}', $v, $this->fileName);
            }

            if (!file_exists($this->destination))
            {
                mkdir($this->destination);
            }

            $pathDestination = $this->destination . '/' . $this->fileName;
            list($_, $pathWithoutRoot) = explode('vendor/../', $pathDestination);
            file_put_contents($pathDestination, $this->contents);

            return $pathWithoutRoot;
        }

        throw new \Exception('Make sure you have set contents, destination and fileName');
    }
}