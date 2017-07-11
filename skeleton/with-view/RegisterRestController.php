<?php

namespace App\Components\Auth\Controllers;

use App\Components\Auth\Managers\RegisterFormFields;
use App\Components\Auth\Storages\AuthModel;
use Simplon\Core\Data\ResponseRestData;
use Simplon\Core\Utils\Exceptions\ClientException;
use Simplon\Core\Utils\Form\FormWrapper;
use Simplon\Form\FormError;
use Simplon\Form\FormFields;
use Simplon\Mysql\MysqlException;
use Simplon\Mysql\QueryBuilder\CreateQueryBuilder;

/**
 * @package App\Components\Auth\Controllers
 */
class RegisterRestController extends BaseRestController
{
    /**
     * @param array $params
     *
     * @return ResponseRestData
     * @throws ClientException
     * @throws FormError
     * @throws MysqlException
     */
    public function __invoke(array $params): ResponseRestData
    {
        $authStorage = $this->getContext()->getAuthStorage();

        $formFields = new RegisterFormFields($this->getContext()->getAuthStorage());
        $validator = (new FormWrapper($formFields->getFormFields(), $this->getRequestData()))->getValidator();

        if ($validator->validate()->isValid())
        {
            $model = $authStorage->create(
                (new CreateQueryBuilder())->setModel(
                    $authStorage
                        ->getModel()
                        ->setAccount(FormFields::getVal($formFields->getFormFields(), RegisterFormFields::FIELD_ACCOUNT))
                        ->setEmail(FormFields::getVal($formFields->getFormFields(), RegisterFormFields::FIELD_EMAIL))
                )
            );

            return $this->respond([
                'status' => 'OK',
                'token'  => $model->getToken(),
                'secret' => $model->getSecret(),
            ]);
        }

        throw (new ClientException())->requestHasInvalidData([
            'reason' => 'registration failed due to invalid/missing fields',
            'fields' => $validator->getErrorMessages(),
        ]);
    }

    /**
     * @return array
     */
    protected function getRequestData(): array
    {
        $payload = $this->getPayload();

        if (empty($payload[RegisterFormFields::FIELD_ACCOUNT]))
        {
            $payload[RegisterFormFields::FIELD_ACCOUNT] = null;
        }

        if (empty($payload[RegisterFormFields::FIELD_EMAIL]))
        {
            $payload[RegisterFormFields::FIELD_EMAIL] = null;
        }

        return $payload;
    }
}