<?php

/**
 * Form Building class
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */

namespace Forms;

class FormBuilder extends \Phalcon\Mvc\User\Component
{

    /**
     * Form Title
     * @var string
     */
    public $title = 'Form Title';

    /**
     * Form Subtitle
     * @var string
     */
    public $subtitle = '';

    /**
     * Form Description
     * @var string
     */
    public $description = '';

    /**
     * Outer padding ratio (out of 12)
     * @var int
     */
    public $outerRatio = 1;

    /**
     * Inner form container ratio (out of 12)
     * @var int
     */
    public $innerRatio = 10;

    /**
     * Submit button text
     * @var string
     */
    public $submitButton = 'Submit';

    /**
     * Cancel button text
     * @var string
     */
    public $cancelButton = 'Cancel';

    /**
     * Cancel button link
     * @var string
     */
    public $cancelHref = '/';

    /**
     * If a captcha is needed
     * @var bool
     */
    public $hasCaptcha = false;

    /**
     * Array of fields added to form
     * @var array
     */
    public $fields = [];

    /**
     * String of the form's html
     * @var string
     */
    public $html = false;

    /**
     * Array of javascripts needed for the form
     * @var array
     */
    public $js = [];

    /**
     * Renders a .volt file with supplied variables
     * @param string $file
     * @param array $variables
     * @return string
     */
    public function renderFile($file, $variables)
    {
        $view = clone $this->view;
        $view->setViewsDir(__DIR__);
        foreach ($variables as $key => $value) {
            $view->setVar($key, $value);
        }
        $view->start();
        $view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
        $view->render('templates', $file);
        $view->finish();
        return $view->getContent();
    }

    /**
     * Renders a field class
     * @param object $field
     * @return string
     */
    public function renderField($field)
    {
        $properties = [];
        foreach ($field as $key => $value) {
            $properties[$key] = $value;
        }

        $content = $this->renderFile($field->template, $properties);
        $matches = [];
        preg_match("#\<script type=\"text/javascript\">(.*?)\</script\>#is", $content, $matches);
        if (count($matches)) {
            $this->js[] = $matches[1];
            $content = preg_replace('#\<script type=\"text/javascript\">(.*?)\</script\>#si', '', $content);
        }
        return $content;
    }

    /**
     * Add a field to the form
     * @param \Forms\Field $field
     * @return \Forms\FormBuilder $this
     */
    public function addField($field)
    {
        $this->fields[] = $field;
        if ($field->repeat) {
            $repeat = clone $field;
            $repeat->key = 'repeat' . $repeat->key;
            $repeat->label = 'Repeat ' . $repeat->label;
            $repeat->repeat = false;
            $repeat->isRepeat = true;
            $this->fields[] = $repeat;
        }
        return $this;
    }

    /**
     * Generate the full form's html
     */
    public function render()
    {
        $fields = [];
        foreach ($this->fields as $field) {
            if ($this->request->isPost()) {
                if ($field->template == 'checkboxgroup') {
                    foreach ($field->options as $option) {
                        if ($this->request->getPost($option->key)) {
                            $field->setDefault($option->key, $this->request->getPost($option->key));
                        }
                    }
                }
                else {
                    if ($this->request->getPost($field->key)) {
                        $field->setDefault($this->request->getPost($field->key));
                    }
                }
            }
            $fields[] = $field;
        }
        $variables = [
            'securityToken' => $this->auth->getSecurityField(),
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'outerRatio' => $this->outerRatio,
            'innerRatio' => $this->innerRatio,
            'submitButton' => $this->submitButton,
            'cancelButton' => $this->cancelButton,
            'cancelHref' => $this->cancelHref,
            'fields' => $fields
        ];
        $this->html = $this->renderFile('base', $variables);

        if (!is_file("{$this->view->getViewsDir()}{$this->dispatcher->getControllerName()}/{$this->dispatcher->getActionName()}.volt")) {
            $this->view->pick('layouts/formbase');
        }
    }

    /**
     * Get the form's html
     * @return string
     */
    public function getHtml()
    {
        if (!$this->html) {
            $this->render();
        }
        return $this->html;
    }

    /**
     * Concat all the js with newlines
     * @return string
     */
    public function getJS()
    {
        $string = '';
        foreach ($this->js as $js) {
            $string .= $js . PHP_EOL;
        }
        return $string;
    }

    /**
     * Validates the required fields, prints out POST data if $test = true
     * @return boolean
     */
    public function validate()
    {
        if (!$this->request->isPost())
            return;

        if (!$this->auth->checkToken()) {
            $field->errorMessage = 'Invalid Anti-CRSF token.';
            return false;
        }

        $result = true;
        foreach ($this->fields as $field) {
            if (!$field->validate($_POST)) {
                $result = false;
                $field->class .= ' invalid';
            }
            if ($field->template == 'captcha') {
                if (!$this->captcha->verify()) {
                    $field->errorMessage = 'Invalid Captcha.';
                }
            }
        }
        return $result;
    }

    /**
     * Adds post data to a supplied model, a translation array can be supplied for post key -> model key
     * @param \Phalcon\Mvc\Model $model
     * @param array $translation
     * @returns \Phalcon\Mvc\Model $model
     */
    public function addToModel($model, $translation = false)
    {
        if ($translation) {
            foreach ($translation as $postKey => $modelKey) {
                if (is_int($postKey)) {
                    $postKey = $modelKey;
                }
                $model->$modelKey = $this->request->getPost($postKey, 'trim');
            }
        }
        else {
            foreach ($this->fields as $field) {
                $key = $field->key;
                if (in_array($key, $model->columnMap())) {
                    $model->$key = $this->request->getPost($key, 'trim');
                }
            }
        }
        return $model;
    }

    /**
     * Saves form data to a loose data format needs models: Looseforms, Formfields, Formentrys, Formdatas
     * 
     * @param string $name
     * @param int $user_id
     * @return \Qukforms
     */
    public function saveData($name, $user_id)
    {
        $form = \Looseforms::findFirstByName($name);
        if (!$form) {
            $form = new \Looseforms();
            $form->name = $name;
            $form->user_id = $user_id;
            $form->private = 1;
            $form->save();

            foreach ($this->fields as $field) {
                if ($field->template == 'captcha')
                    continue;

                $fieldModel = new \Formfields();
                $fieldModel->fieldKey = $field->key;
                $fieldModel->fieldName = $field->label;
                $fieldModel->form_id = $form->id;
                $fieldModel->save();
            }
        }

        $entry = new \Formentrys();
        $entry->date = date('Y-m-d H:i:s');
        $entry->user_id = $this->auth->user_id;
        $entry->form_id = $form->id;
        $entry->save();

        foreach ($this->fields as $field) {
            $formdata = new \Formdatas();
            $formdata->formentry_id = $entry->id;
            $field_id = \Formfields::findFirstByFieldKey($field->key)->id;
            if ($field_id) {
                $formdata->field_id = $field_id;
                $formdata->value = $this->request->getPost($field->key, 'trim');
                $formdata->save();
            }
        }

        return $form;
    }

}
