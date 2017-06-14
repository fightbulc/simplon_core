<?php

namespace Simplon\Core\Utils;

use Simplon\Helper\SecurityUtil;
use Simplon\Mysql\Crud\CrudStoreInterface;
use Simplon\Mysql\QueryBuilder\ReadQueryBuilder;

/**
 * @package App\Utils
 */
class StorageUtil
{
    /**
     * @param CrudStoreInterface $storage
     * @param string $tokenColumnName
     * @param int $length
     * @param string $prefix
     *
     * @return string
     */
    public static function getUniquePubToken(CrudStoreInterface $storage, string $tokenColumnName = 'pub_token', int $length = 12, ?string $prefix = null)
    {
        $token = null;
        $isUnique = false;
        $characters = SecurityUtil::TOKEN_UPPERCASE_LETTERS_NUMBERS;

        while ($isUnique === false)
        {
            $token = SecurityUtil::createRandomToken($length, $prefix, $characters);
            $isUnique = $storage->readOne((new ReadQueryBuilder())->addCondition($tokenColumnName, $token)) === null;
        }

        return $token;
    }
}