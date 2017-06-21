<?php

namespace Simplon\Core\Utils\Form;

use Simplon\Form\FormError;
use Simplon\Form\FormFields;
use Simplon\Form\View\Elements\SubmitElement;
use Simplon\Form\View\FormView;
use Simplon\Form\View\FormViewBlock;
use Simplon\Form\View\FormViewRow;
use Simplon\Locale\Locale;

/**
 * @package Simplon\Core\Utils\Form
 */
abstract class BaseFormView implements FormViewInterface
{
    const BLOCK_DEFAULT = 'default';

    /**
     * @var FormView
     */
    protected $view;
    /**
     * @var Locale
     */
    protected $locale;
    /**
     * @var FormFields
     */
    protected $fields;

    /**
     * @param Locale $locale
     * @param FormFields $fields
     */
    public function __construct(Locale $locale, FormFields $fields)
    {
        $this->locale = $locale;
        $this->fields = $fields;
    }

    /**
     * @return string
     */
    abstract protected function getUrl(): string;

    /**
     * @return FormViewBlock[]
     */
    abstract protected function getBlocks(): array;

    /**
     * @return FormView
     * @throws FormError
     */
    public function getView(): FormView
    {
        if (!$this->view)
        {
            $this->view = $this
                ->applyOnView((new FormView())->setSubmitElement($this->getSubmitElement()))
                ->setUrl($this->getUrl())
                ->addBlocks($this->getBlocks())
            ;
        }

        return $this->view;
    }

    /**
     * @param FormView $view
     *
     * @return FormView
     */
    protected function applyOnView(FormView $view): FormView
    {
        return $view;
    }

    /**
     * @return SubmitElement
     */
    protected function getSubmitElement(): SubmitElement
    {
        return new SubmitElement($this->getSubmitLabel(), $this->getSubmitClasses());
    }

    /**
     * @return string
     */
    protected function getSubmitLabel(): string
    {
        return $this->getLocale()->get('form-default-submit-label');
    }

    /**
     * @return array
     */
    protected function getSubmitClasses(): array
    {
        return [];
    }

    /**
     * @return FormFields
     */
    protected function getFields(): FormFields
    {
        return $this->fields;
    }

    /**
     * @return Locale
     */
    protected function getLocale(): Locale
    {
        return $this->locale;
    }

    /**
     * @return FormViewRow
     */
    protected function getFormViewRow(): FormViewRow
    {
        return new FormViewRow();
    }
}