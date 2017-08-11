<?php

namespace Simplon\Core\Utils\DataFields;

use Simplon\Helper\CastAway;

trait MetaJsonAwareDataTrait
{
    /**
     * @var string
     */
    protected $metaJson;

    /**
     * @return null|string
     */
    public function getMetaJson(): ?string
    {
        return $this->metaJson;
    }

    /**
     * @return bool
     */
    public function hasMetaData(): bool
    {
        return $this->metaJson !== null;
    }

    /**
     * @param string $metaJson
     *
     * @return static
     */
    public function setMetaJson(?string $metaJson = null)
    {
        $this->metaJson = null;

        if ($metaJson)
        {
            // let's assure that all keys are in order
            // so we can calc a checksum later

            $this->setMetaArray(
                CastAway::jsonToArray($metaJson)
            );
        }

        return $this;
    }

    /**
     * @param null|string $key
     *
     * @return mixed
     */
    public function getMetaArray(?string $key = null)
    {
        if ($this->getMetaJson())
        {
            $data = CastAway::jsonToArray($this->getMetaJson());

            if (!$key)
            {
                return $data;
            }

            if (isset($data[$key]))
            {
                return $data[$key];
            }
        }

        return [];
    }

    /**
     * @param string $key
     * @param mixed $val
     *
     * @return static
     */
    public function setMetaItemArray(string $key, $val)
    {
        $data = $this->getMetaArray();
        $data[$key] = $val;
        $this->setMetaArray($data);

        return $this;
    }

    /**
     * @param array $metasArray
     *
     * @return static
     */
    public function setMetaArray(array $metasArray)
    {
        // sorting keys so we can
        // calc a checksum later

        ksort($metasArray);

        $this->metaJson = CastAway::arrayToJson($metasArray);

        return $this;
    }
}