<?php

/**
 * Form Building class
 *
 * @category
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace CodingBeard\Forms;

use Formdatas;
use Formentrys;
use Formfields;
use Qukforms;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\User\Component;
use Phalcon\Mvc\View;

class FormBuilder extends Component
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
     * Array of clones of self which are stages in a multi-part form
     * @var array
     */
    public $stages = [];

    /**
     * Renders a .volt file with supplied variables
     * @param string $file
     * @param array $variables
     * @return string
     */
    public function renderFile($file, $variables)
    {
        $view = clone $this->formview;
        $view->setViewsDir(__DIR__ . '/');
        foreach ($variables as $key => $value) {
            $view->setVar($key, $value);
        }
        $view->start();
        $view->setRenderLevel(View::LEVEL_ACTION_VIEW);
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
     * @param $field
     * @return FormBuilder $this
     */
    public function addField($field)
    {
        $this->fields[] = $field;
        if (isset($field->repeat)) {
            if ($field->repeat) {
                $repeat = clone $field;
                $repeat->key = 'repeat' . $repeat->key;
                $repeat->label = 'Repeat ' . $repeat->label;
                $repeat->repeat = false;
                $repeat->isRepeat = true;
                $this->fields[] = $repeat;
            }
        }

        return $this;
    }

    /**
     * Return the string of a field
     * @param string $fieldName
     * @param array $properties
     * @return string
     */
    public function field($fieldName, $properties)
    {
        $fieldName = '\Forms\Fields\\' . ucfirst($fieldName);
        $field = new $fieldName($properties);

        return $this->renderField($field);
    }

    /**
     * Add another stage to this form
     * @param FormBuilder $form
     */
    public function addStage(self $form)
    {
        $this->stages[] = $form;
    }

    /**
     * @param int $stage
     * @return bool
     */
    public function setStage($stage)
    {
        if (isset($this->stages[$stage])) {
            foreach ($this->stages[$stage] as $property => $value) {
                if ($property == 'stages') {
                    continue;
                }
                $this->$property = $value;
            }

            return true;
        }

        return false;
    }

    /**
     * Generate the full form's html
     */
    public function render()
    {
        foreach ($this->fields as $field) {
            if ($field->template == 'checkboxgroup') {
                foreach ($field->options as $option) {
                    if (preg_match("#^(.+)\[(.*)\]$#is", $option->key, $matches)) {
                        if ($this->request->hasPost($matches[1])) {
                            $field->setDefault($option->key, $this->request->getPost($matches[1])[$matches[2]]);
                        }
                    }
                    else {
                        if ($this->request->hasPost($option->key)) {
                            $field->setDefault($option->key, $this->request->getPost($option->key));
                        }
                    }
                }
            }
            else {
                if (preg_match("#^(.+)\[(.*)\]$#is", $field->key, $matches)) {
                    if ($this->request->hasPost($matches[1])) {
                        $field->setDefault($this->request->getPost($matches[1])[$matches[2]]);
                    }
                }
                else {
                    if ($this->request->hasPost($field->key)) {
                        $field->setDefault($this->request->getPost($field->key));
                    }
                }
            }
        }
        $variables = [
            'title'       => $this->title,
            'subtitle'    => $this->subtitle,
            'description' => $this->description,
            'outerRatio'  => $this->outerRatio,
            'innerRatio'  => $this->innerRatio,
            'submitButton' => $this->submitButton,
            'cancelButton' => $this->cancelButton,
            'cancelHref'  => $this->cancelHref,
            'fields'      => $this->fields,
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
            return false;

        if ($this->auth) {
            if (!$this->auth->checkToken($_POST)) {
                die('Invalid Anti-CRSF token.');

                return false;
            }
        }

        $result = true;
        foreach ($this->fields as $field) {
            if (!$field->validate($_POST)) {
                $result = false;
                $field->class .= ' invalid';
            }
            if ($field->template == 'captcha') {
                if (!$this->captcha->verify()) {
                    $result = false;
                    $field->errorMessage = 'Invalid Captcha.';
                }
            }
        }

        return $result;
    }

    /**
     * Adds post data to a supplied model, a translation array can be supplied for post key -> model key
     * @param Model $model
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
                    if (in_array($field->template, ['checkbox', 'switchbox',])) {
                        $value = ($this->request->getPost($key, 'trim') == 'on') ? 1 : 0;
                    }
                    else {
                        $value = $this->request->getPost($key, 'trim');
                    }
                    if (!$value && substr($key, -3) == '_id') {
                        $value = null;
                    }
                    $model->$key = $value;
                }
            }
        }

        return $model;
    }

    /**
     * Saves form data to a loose data format needs models: Qukforms, Formfields, Formentrys, Formdatas
     *
     * @param string $name
     * @param int $user_id
     * @return Qukforms
     */
    public function saveData($name, $user_id, $permissions = [])
    {
        $form = Qukforms::findFirstByName($name);
        if (!$form) {
            $form = new Qukforms();
            $form->name = $name;
            $form->user_id = $user_id;
            $form->permissions = $permissions;
            $form->private = 1;
            $form->save();

            if (count($this->stages)) {
                foreach ($this->stages as $key => $stage) {

                    $fieldModel = new Formfields();
                    $fieldModel->fieldKey = 'stage' . $key;
                    $fieldModel->fieldName = 'Stage ' . $key;
                    $fieldModel->form_id = $form->id;
                    $fieldModel->save();

                    foreach ($stage->fields as $field) {
                        if ($field->template == 'captcha' || $field->template == 'freetext')
                            continue;

                        $fieldModel = new Formfields();
                        $fieldModel->fieldKey = $field->key;
                        $fieldModel->fieldName = $field->label;
                        if (strlen($field->sublabel)) {
                            $fieldModel->fieldName .= ' [' . strip_tags($field->sublabel) . ']';
                        }
                        $fieldModel->form_id = $form->id;
                        $fieldModel->save();
                    }
                }
            }
            else {
                foreach ($this->fields as $field) {
                    if ($field->template == 'captcha' || $field->template == 'freetext')
                        continue;

                    $fieldModel = new Formfields();
                    $fieldModel->fieldKey = $field->key;
                    $fieldModel->fieldName = $field->label;
                    $fieldModel->form_id = $form->id;
                    $fieldModel->save();
                }
            }
        }

        $entry = new Formentrys();
        $entry->date = date('Y-m-d H:i:s');
        $entry->user_id = $this->auth->user_id;
        $entry->form_id = $form->id;
        $entry->save();

        if (count($this->stages)) {
            foreach ($this->stages as $key => $stage) {

                $formdata = new Formdatas();
                $formdata->formentry_id = $entry->id;
                $field_id = Formfields::findFirstByFieldKey('stage' . $key)->id;
                if ($field_id) {
                    $formdata->field_id = $field_id;
                    $formdata->value = $stage->title;
                    $formdata->save();
                }

                foreach ($stage->fields as $field) {
                    $formdata = new Formdatas();
                    $formdata->formentry_id = $entry->id;
                    $field_id = Formfields::findFirstByFieldKey($field->key)->id;
                    if ($field_id) {
                        $formdata->field_id = $field_id;
                        if ($field->template == 'checkbox' || $field->template == 'switchbox') {
                            if ($_POST[$key][$field->key] == 'on') {
                                $value = 'Yes';
                            }
                            else {
                                $value = 'No';
                            }
                        }
                        elseif ($field->template == 'checkboxgroup') {
                            $value = $_POST[$key][$field->key];
                            if (is_array($value)) {
                                array_walk($value, function (&$value, $key) {
                                    if ($value == 'on') {
                                        $value = $key;
                                    }
                                });
                                $value = implode(', ', $value);
                            }
                        }
                        else {
                            $value = $_POST[$key][$field->key];
                        }
                        $formdata->value = $value;
                        $formdata->save();
                    }
                }
            }
        }
        else {
            foreach ($this->fields as $field) {
                $formdata = new Formdatas();
                $formdata->formentry_id = $entry->id;
                $field_id = Formfields::findFirstByFieldKey($field->key)->id;
                if ($field_id) {
                    $formdata->field_id = $field_id;
                    $formdata->value = $this->request->getPost($field->key, 'trim');
                    $formdata->save();
                }
            }
        }

        return $entry;
    }

}
