<?php

namespace Simplon\Core\Data;

use Simplon\Core\Interfaces\DataInterface;
use Simplon\Core\Utils\Exceptions\ServerException;

/**
 * Class Data
 * @package Simplon\Core\Data
 */
abstract class Data implements DataInterface
{
    /**
     * @param array $data
     * @param bool $throwErrorOnMissingProperty
     *
     * @return static
     * @throws ServerException
     */
    public function fromArray(array $data, bool $throwErrorOnMissingProperty = false)
    {
        if ($data)
        {
            foreach ($data as $fieldName => $val)
            {
                // format field name
                if (strpos($fieldName, '_') !== false)
                {
                    $fieldName = self::camelCaseString($fieldName);
                }

                $setMethodName = 'set' . ucfirst($fieldName);

                // set on setter
                if (method_exists($this, $setMethodName))
                {
                    $this->$setMethodName($val);
                    continue;
                }

                // set on field
                if (property_exists($this, $fieldName))
                {
                    $this->$fieldName = $val;
                    continue;
                }

                if ($throwErrorOnMissingProperty)
                {
                    throw (new ServerException())->internalError([
                        'reason'   => 'missing property to set value on data object',
                        'object'   => get_called_class(),
                        'property' => $fieldName,
                        'context'  => $data,
                    ]);
                }
            }
        }

        return $this;
    }

    /**
     * @param bool $snakeCase
     * @param bool $throwErrorOnMissingProperty
     *
     * @return array
     * @throws ServerException
     */
    public function toArray(bool $snakeCase = true, bool $throwErrorOnMissingProperty = false): array
    {
        $result = [];

        $visibleFields = get_class_vars(get_called_class());

        // render column names
        foreach ($visibleFields as $fieldName => $value)
        {
            $propertyName = $fieldName;
            $getMethodName = 'get' . ucfirst($fieldName);

            // format field name
            if ($snakeCase === true && strpos($fieldName, '_') === false)
            {
                $fieldName = self::snakeCaseString($fieldName);
            }

            // get from getter
            if (method_exists($this, $getMethodName))
            {
                $result[$fieldName] = $this->$getMethodName();
                continue;
            }

            // get from field
            if (property_exists($this, $propertyName))
            {
                $result[$fieldName] = $this->$propertyName;
                continue;
            }

            if ($throwErrorOnMissingProperty)
            {
                throw (new ServerException())->internalError([
                    'reason'   => 'missing property to get value from data object',
                    'object'   => get_called_class(),
                    'property' => $propertyName,
                ]);
            }
        }

        return $result;
    }

    /**
     * @param bool $snakeCase
     *
     * @return string
     * @throws ServerException
     */
    public function toJson(bool $snakeCase = true): string
    {
        return json_encode(
            $this->toArray($snakeCase)
        );
    }

    /**
     * @param string $json
     *
     * @return Data
     * @throws ServerException
     */
    public function fromJson(string $json)
    {
        return $this->fromArray(
            json_decode($json, true)
        );
    }

    /**
     * @param $string
     *
     * @return string
     */
    protected static function snakeCaseString($string)
    {
        return strtolower(preg_replace('/([A-Z1-9])/', '_\\1', $string));
    }

    /**
     * @param string $string
     *
     * @return string
     */
    protected static function camelCaseString($string)
    {
        $string = strtolower($string);
        $string = ucwords(str_replace('_', ' ', $string));

        return lcfirst(str_replace(' ', '', $string));
    }
}