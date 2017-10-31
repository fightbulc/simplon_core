<?php

namespace Simplon\Core\Utils\Form;

use Simplon\Form\FormError;
use Simplon\Form\FormFields;
use Simplon\Form\FormValidator;

class FormWrapper
{
    /**
     * @var BaseForm
     */
    private $form;
    /**
     * @var FormValidator
     */
    protected $validator;

    /**
     * @param BaseForm $form
     * @param array $requestData
     * @param array $initialData
     *
     * @throws FormError
     */
    public function __construct(BaseForm $form, array $requestData = [], array $initialData = [])
    {
        $this->form = $form;
        $this->validator = (new FormValidator($requestData))->addFields($this->getFields());

        if (!$this->validator->hasBeenSubmitted())
        {
            $this->getFields()->applyInitialData($initialData);
        }
    }

    /**
     * @return FormFields
     * @throws FormError
     */
    public function getFields(): FormFields
    {
        return $this->form->getFields();
    }

    /**
     * @param string $fieldId
     * @param null|string $castType
     *
     * @return mixed|null
     * @throws FormError
     */
    public function getVal(string $fieldId, ?string $castType = null)
    {
        $value = $this->getFields()->get($fieldId)->getValue();

        if ($value !== '')
        {
            if ($castType)
            {
                $value = $this->castValue($value, $castType);
            }

            return $value;
        }

        return null;
    }

    /**
     * @return FormValidator
     */
    public function getValidator(): FormValidator
    {
        return $this->validator;
    }

    /**
     * @return bool
     * @throws FormError
     */
    public function isValid(): bool
    {
        return $this->validator->validate()->isValid();
    }

    /**
     * @param $value
     * @param string $type
     *
     * @return mixed
     */
    private function castValue($value, string $type)
    {
        switch ($type)
        {
            case 'int':
                $value = (int)$value;
                break;
            case 'float':
                $value = (float)$value;
                break;
            case 'bool':
                $value = (bool)$value;
                break;
            default:
        }

        return $value;
    }
}