<?php

namespace App\Components\Auth\Storages;

use Simplon\Helper\SecurityUtil;
use Simplon\Mysql\Crud\CrudStore;
use Simplon\Mysql\MysqlException;
use Simplon\Mysql\QueryBuilder\CreateQueryBuilder;
use Simplon\Mysql\QueryBuilder\DeleteQueryBuilder;
use Simplon\Mysql\QueryBuilder\ReadQueryBuilder;
use Simplon\Mysql\QueryBuilder\UpdateQueryBuilder;
use Simplon\Mysql\Utils\StorageUtil;
use Simplon\Mysql\Utils\UniqueTokenOptions;

/**
 * @package App\Components\Auth\Storages
 */
class AuthStorage extends CrudStore
{
    const TABLE_NAME = 'auth';

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    /**
     * @return AuthModel
     */
    public function getModel(): AuthModel
    {
        return new AuthModel();
    }

    /**
     * @return string
     */
    public function buildUniqueToken(): string
    {
        $options = (new UniqueTokenOptions())->setLength(22)->setCharacters(SecurityUtil::TOKEN_ALL_CASE_LETTERS_NUMBERS);

        return StorageUtil::getUniqueToken($this, $options);
    }

    /**
     * @return string
     */
    public function buildSecret(): string
    {
        return SecurityUtil::createRandomToken(32);
    }

    /**
     * @param CreateQueryBuilder $builder
     *
     * @return AuthModel|null
     * @throws MysqlException
     */
    public function create(CreateQueryBuilder $builder): ?AuthModel
    {
        /** @var AuthModel $model */
        $model = $builder->getModel();

        //
        // build token
        //

        if (empty($model->getToken()))
        {
            $model->setToken($this->buildUniqueToken());
        }

        //
        // build secret
        //

        if (empty($model->getSecret()))
        {
            $model->setSecret($this->buildSecret());
        }

        //
        // make accessible
        //

        $model->setAccessible(AuthModel::VAL_ACCESSIBLE_YES);

        //
        // persist
        //

        /** @var AuthModel|null $model */
        $model = $this->crudCreate($builder->setModel($model));

        return $model;
    }

    /**
     * @param null|ReadQueryBuilder $builder
     *
     * @return AuthModel[]|null
     * @throws MysqlException
     */
    public function read(?ReadQueryBuilder $builder = null): ?array
    {
        /** @var AuthModel[]|null $models */
        $models = $this->crudRead($builder);

        return $models;
    }

    /**
     * @param ReadQueryBuilder $builder
     *
     * @return AuthModel|null
     * @throws MysqlException
     */
    public function readOne(ReadQueryBuilder $builder): ?AuthModel
    {
        /** @var AuthModel|null $model */
        $model = $this->crudReadOne($builder);

        return $model;
    }

    /**
     * @param UpdateQueryBuilder $builder
     *
     * @return AuthModel
     * @throws MysqlException
     */
    public function update(UpdateQueryBuilder $builder): AuthModel
    {
        /** @var AuthModel $model */
        $model = $this->crudUpdate($builder);

        return $model;
    }

    /**
     * @param DeleteQueryBuilder $builder
     *
     * @return bool
     * @throws MysqlException
     */
    public function delete(DeleteQueryBuilder $builder): bool
    {
        return $this->crudDelete($builder);
    }
}