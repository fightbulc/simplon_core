<?php

namespace App\Components\Auth\Storages;

use Simplon\Core\Utils\DataFields\IdAwareDataTrait;
use Simplon\Core\Utils\DataFields\TimeAwareDataTrait;
use Simplon\Core\Utils\DataFields\TokenAwareDataTrait;
use Simplon\Helper\CastAway;
use Simplon\Mysql\Crud\CrudModel;

/**
 * @package App\Components\Auth\Storages
 */
class AuthModel extends CrudModel
{
    use IdAwareDataTrait;
    use TokenAwareDataTrait;
    use TimeAwareDataTrait;

    const COLUMN_ID = 'id';
    const COLUMN_TOKEN = 'token';
    const COLUMN_SECRET = 'secret';
    const COLUMN_ACCOUNT = 'account';
    const COLUMN_EMAIL = 'email';
    const COLUMN_ACCESSIBLE = 'accessible';
    const COLUMN_CREATED_AT = 'created_at';
    const COLUMN_UPDATED_AT = 'updated_at';

    const VAL_ACCESSIBLE_YES = 1;
    const VAL_ACCESSIBLE_NO = 0;

    /**
     * @var string
     */
    protected $secret;
    /**
     * @var string
     */
    protected $account;
    /**
     * @var string
     */
    protected $email;
    /**
     * @var int
     */
    protected $accessible;

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     *
     * @return AuthModel
     */
    public function setSecret(string $secret): AuthModel
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * @return string
     */
    public function getAccount(): string
    {
        return $this->account;
    }

    /**
     * @param string $account
     *
     * @return AuthModel
     */
    public function setAccount(string $account): AuthModel
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return AuthModel
     */
    public function setEmail(string $email): AuthModel
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return int
     */
    public function getAccessible(): int
    {
        return CastAway::toInt($this->accessible);
    }

    /**
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->getAccessible() === self::VAL_ACCESSIBLE_YES;
    }

    /**
     * @param int $accessible
     *
     * @return AuthModel
     */
    public function setAccessible(int $accessible): AuthModel
    {
        $this->accessible = $accessible;

        return $this;
    }
}