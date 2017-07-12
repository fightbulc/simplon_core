<?php

namespace Simplon\Core\Utils\Form;

use Simplon\Form\Data\FormField;
use Simplon\Form\FormError;
use Simplon\Form\FormFields;

/**
 * @package Simplon\Core\Utils\Form
 */
abstract class BaseForm
{
    /**
     * @var FormFields
     */
    protected $formFields;

    /**
     * @return FormFields
     * @throws FormError
     */
    public function getFields(): FormFields
    {
        if (!$this->formFields)
        {
            $this->formFields = new FormFields();

            foreach ($this->buildFields() as $field)
            {
                $this->formFields->add($field);
            }
        }

        return $this->formFields;
    }

    /**
     * @param string $fieldId
     *
     * @return mixed|null
     * @throws FormError
     */
    public function getVal(string $fieldId)
    {
        $value = $this->getFields()->get($fieldId)->getValue();

        if ($value !== '')
        {
            return $value;
        }

        return null;
    }

    /**
     * @return FormField[]
     */
    abstract protected function buildFields(): array;
}