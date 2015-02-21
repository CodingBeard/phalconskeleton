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

class FormBuilder extends \Phalcon\Mvc\User\Plugin
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
    public $description = 'Form Description';

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
     * Array of javascripts needed for the form
     * @var array
     */
    public $js = [];

    /**
     * 
     * @param \Phalcon\DI $dependencyInjector
     */
    public function __construct($dependencyInjector)
    {
        $this->_dependencyInjector = $dependencyInjector;
    }

    /**
     * Renders a .volt file with supplied variables
     * @param string $file
     * @param array $variables
     * @return string
     */
    public function render($file, $variables)
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
        $content = $view->getContent();

        $matches = [];
        preg_match("#\<script type=\"text/javascript\">(.*?)\</script\>#is", $content, $matches);
        if (count($matches)) {
            $this->js[] = $matches[1];
            $content = preg_replace('#\<script type=\"text/javascript\">(.*?)\</script\>#si', '', $content);
        }
        return $content;
    }

    /**
     * Saves post data to a supplied model, a translation array can be supplied for post key -> model key
     * @param \Phalcon\Mvc\Model $model
     * @param array $translation
     */
    public function saveToModel($model, $translation = false)
    {
        if ($translation) {
            foreach ($translation as $postKey => $modelKey) {
                $model->$modelKey = $this->request->getPost($postKey, 'trim');
            }
        }
        else {
            foreach ($model->columnMap() as $key) {
                if ($key == 'id')
                    continue;
                $model->$key = $this->request->getPost($key, 'trim');
            }
        }
        $model->save();
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
        $form = \Looseforms::findFirst([
            'name = :a:',
            'bind' => ['a' => $name]
        ]);
        if (!$form) {
            $form = new \Looseforms();
            $form->name = $name;
            $form->user_id = $user_id;
            $form->private = 1;
            $form->save();

            foreach ($this->fields as $field) {
                if ($field['type'] == 'captcha')
                    continue;

                $fieldModel = new \Formfields();
                $fieldModel->fieldKey = $field['variables']['key'];
                $fieldModel->fieldName = $field['variables']['label'];
                $fieldModel->form_id = $form->id;
                $fieldModel->save();
            }
        }

        $entry = new \Formentrys();
        $entry->date = date('Y-m-d H:i:s');
        $entry->user_id = $this->auth->id;
        $entry->form_id = $form->id;
        $entry->save();

        foreach ($this->fields as $field) {
            $formdata = new \Formdatas();
            $formdata->formentry_id = $entry->id;
            $field_id = \Formfields::findFirst([
                'fieldKey = :a:',
                'bind' => ['a' => $field['variables']['key']]
            ])->id;
            if ($field_id) {
                $formdata->field_id = $field_id;
                $formdata->value = $this->request->getPost($field['variables']['key'], 'trim');
                $formdata->save();
            }
        }

        return $form;
    }

    /**
     * Validates the required fields, prints out POST data if $test = true
     * TODO: optional regex matching
     * @param bool $test
     * @return boolean
     */
    public function validate($test = false)
    {
        if (!$this->request->isPost())
            return;
        if ($test) {
            echo '<pre>';
            print_r($_POST);
            die;
        }
        $result = true;
        if ($this->hasCaptcha) {
            if (!$this->captcha->verify()) {
                $this->flashSession->error("Invalid Captcha.");
                $result = false;
            }
        }
        foreach ($this->fields as $key => $field) {
            if ($field['variables']['required']) {
                if (!strlen($this->request->getPost($field['variables']['key'], 'trim'))) {
                    $this->fields[$key]['validate'] = false;
                    $result = false;
                }
            }
        }
        return $result;
    }

    /**
     * Generate the full form's html
     * @return string
     */
    public function getForm()
    {
        $fields = [];
        foreach ($this->fields as $field) {
            if ($this->request->getPost($field['variables']['key'])) {
                $field = $this->setDefaultValue($field, $this->request->getPost($field['variables']['key']));
            }
            if ($field['validate'] === false) {
                $field['variables']['error'] = 'has-error';
            }
            if ($field['variables']['required']) {
                $field['variables']['required'] = 'required';
                $field['variables']['label'] .= ' <strong style="color: red;">*</strong>';
            }
            $fields[] = $this->render($field['type'], $field['variables']);
        }
        $variables = [
            'securityToken' => "<input type='hidden' name='{$this->auth->tokenKey}' value='{$this->auth->token}'/>",
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'outerRatio' => $this->outerRatio,
            'innerRatio' => $this->innerRatio,
            'submitButton' => $this->submitButton,
            'cancelHref' => $this->cancelHref,
            'fields' => $fields
        ];
        return $this->render('base', $variables);
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
     * Sets the default value of a field to be pre-populated
     * @param array $field
     * @param mixed $value
     * @return array
     */
    public function setDefaultValue($field, $value)
    {
        switch ($field['type']) {
            case 'textbox':
            case 'textarea':
            case 'email':
            case 'password':
            case 'number':
                $field['variables']['defaultValue'] = $value;
                break;
            case 'switchbox':
                if ($value)
                    $field['variables']['defaultValue'] = 'checked';
                break;
            case 'radio':
                foreach ($field['variables']['options'] as $key => $option) {
                    if ($option->value == $value) {
                        $field['variables']['options'][$key]->default = 'checked';
                    }
                }
                break;
            case 'select':
                foreach ($field['variables']['options'] as $key => $option) {
                    if ($option->value == $value) {
                        $field['variables']['options'][$key]->default = 'selected';
                    }
                }
            case 'tagbox':
                foreach ($field['variables']['options'] as $key => $option) {
                    foreach ($value as $tagValue) {
                        if ($option->value == $tagValue) {
                            $field['variables']['options'][$key]->default = true;
                        }
                    }
                }
                break;
        }
        return $field;
    }

    /**
     * Add a field to the form
     * @param array $field
     */
    public function addField($field)
    {
        $this->fields[] = $field;
    }

    /**
     * Return an array of Volt template and variables for a text box
      $variables = [
      'key' => '',
      'label' => '',
      'sublabel' => '',
      'required' => false,
      'class' => '',
      'size' => 12,
      'defaultValue' => ''
      ]
     * @param array $variables
     * @return array
     */
    public function textbox($variables = [
        'key' => '',
        'label' => '',
        'sublabel' => '',
        'required' => false,
        'class' => '',
        'size' => 12,
        'defaultValue' => ''
    ])
    {
        if (is_callable($variables['defaultValue']))
            $variables['defaultValue'] = $variables['defaultValue']();

        return ['type' => 'textbox', 'variables' => $variables];
    }

    /**
     * Return an array of Volt template and variables for a textarea
      $variables = [
      'key' => '',
      'label' => '',
      'sublabel' => '',
      'required' => false,
      'class' => '',
      'size' => 12,
      'defaultValue' => ''
      ]
     * @param array $variables
     * @return array
     */
    public function textarea($variables = [
        'key' => '',
        'label' => '',
        'sublabel' => '',
        'required' => false,
        'class' => '',
        'size' => 12,
        'defaultValue' => ''
    ])
    {
        if (is_callable($variables['defaultValue']))
            $variables['defaultValue'] = $variables['defaultValue']();

        return ['type' => 'textarea', 'variables' => $variables];
    }

    /**
     * Return an array of Volt template and variables for a set of radio buttons
      $variables = [
      'key' => '',
      'label' => '',
      'sublabel' => '',
      'required' => false,
      'class' => '',
      'size' => 12,
      'options' => [
      ['value' => '', 'label' => '', 'default' => false],
      ]
      ]
     * @param array $variables
     * @return array
     */
    public function radio($variables = [
        'key' => '',
        'label' => '',
        'sublabel' => '',
        'required' => false,
        'class' => '',
        'size' => 12,
        'options' => [
            ['value' => '', 'label' => '', 'default' => false],
        ]
    ])
    {
        if (is_callable($variables['options']))
            $variables['options'] = $variables['options']();

        foreach ($variables['options'] as $key => $value) {
            if ($value['default'])
                $value['default'] = 'checked';

            $variables['options'][$key] = (object) $value;
        }

        return ['type' => 'radio', 'variables' => $variables];
    }

    /**
     * Return an array of Volt template and variables for a single checkbox
      $variables = [
      'key' => '',
      'label' => '',
      'sublabel' => '',
      'class' => '',
      'size' => 12,
      'defaultValue' => ''
      ]
     * @param array $variables
     * @return array
     */
    public function checkbox($variables = [
        'key' => '',
        'label' => '',
        'sublabel' => '',
        'class' => '',
        'size' => 12,
        'defaultValue' => ''
    ])
    {
        if ($variables['defaultValue'])
            $variables['defaultValue'] = 'checked';

        return ['type' => 'checkbox', 'variables' => $variables];
    }

    /**
     * Return an array of Volt template and variables for a group of checkboxes
      $variables = [
      'label' => '',
      'sublabel' => '',
      'class' => '',
      'size' => 12,
      'options' => [
      ['key' => '', 'label' => '', 'default' => false],
      ]
     * @param array $variables
     * @return array
     */
    public function checkboxgroup($variables = [
        'label' => '',
        'sublabel' => '',
        'class' => '',
        'size' => 12,
        'options' => [
            ['key' => '', 'label' => '', 'default' => false],
        ]
    ])
    {
        if (is_callable($variables['options']))
            $variables['options'] = $variables['options']();

        foreach ($variables['options'] as $key => $value) {
            if ($value['default'])
                $value['default'] = 'checked';

            $variables['options'][$key] = (object) $value;
        }

        return ['type' => 'checkboxgroup', 'variables' => $variables];
    }

    /**
     * Return an array of Volt template and variables for a select dropdown
      $variables = [
      'key' => '',
      'label' => '',
      'sublabel' => '',
      'required' => false,
      'class' => '',
      'size' => 12,
      'options' => [
      ['value' => '', 'label' => '', 'default' => false],
      ]
      ]
     * @param array $variables
     * @return array
     */
    public function select($variables = [
        'key' => '',
        'label' => '',
        'sublabel' => '',
        'required' => false,
        'class' => '',
        'size' => 12,
        'options' => [
            ['value' => '', 'label' => '', 'default' => false],
        ]
    ])
    {
        if (is_callable($variables['options']))
            $variables['options'] = $variables['options']();

        foreach ($variables['options'] as $key => $value) {
            if ($value['default'])
                $value['default'] = 'checked';

            $variables['options'][$key] = (object) $value;
        }

        return ['type' => 'select', 'variables' => $variables];
    }

    /**
     * Return an array of Volt template and variables for a Captcha
     * $guestsOnly causes it to only show for guests
     * @param bool $guestsOnly
     * @return array
     */
    public function captcha($guestsOnly = false)
    {
        if ($guestsOnly && $this->auth->id) {
            $captcha = false;
        }
        else {
            $captcha = true;
            $this->hasCaptcha = true;
        }
        return ['type' => 'captcha', 'variables' => ['captcha' => $captcha]];
    }

    /**
     * Return an array of Volt template and variables for a switch
      $variables = [
      'key' => '',
      'label' => '',
      'sublabel' => '',
      'class' => '',
      'onText' => '',
      'offText' => '',
      'size' => 12,
      'defaultValue' => ''
      ]
     * @param array $variables
     * @return array
     */
    public function switchbox($variables = [
        'key' => '',
        'label' => '',
        'sublabel' => '',
        'class' => '',
        'onText' => '',
        'offText' => '',
        'size' => 12,
        'defaultValue' => ''
    ])
    {
        if (is_callable($variables['defaultValue']))
            $variables['defaultValue'] = $variables['defaultValue']();

        if ($variables['defaultValue'])
            $variables['defaultValue'] = 'checked';

        if (!$variables['onText'])
            $variables['onText'] = 'Yes';
        if (!$variables['offText'])
            $variables['offText'] = 'No';

        return ['type' => 'switchbox', 'variables' => $variables];
    }

    /**
     * Return an array of Volt template and variables for a tag box
      $variables = [
      'key' => '',
      'label' => '',
      'sublabel' => '',
      'required' => false,
      'class' => '',
      'tagLimit' => 'null',
      'options' => [
      ['id' => '', 'label' => '', 'default' => false],
      ]
     * @param array $variables
     * @return array
     */
    public function tagbox($variables = [
        'key' => '',
        'label' => '',
        'sublabel' => '',
        'required' => false,
        'class' => '',
        'tagLimit' => 'null',
        'options' => [
            ['id' => '', 'label' => '', 'default' => false],
        ]
    ])
    {
        if (is_callable($variables['options']))
            $variables['options'] = $variables['options']();

        if (!$variables['tagLimit'])
            $variables['tagLimit'] = 'null';

        foreach ($variables['options'] as $key => $value) {
            $variables['tagValues'][$value['label']] = $value['value'];
            $variables['tagLabels'][] = $value['label'];

            $variables['options'][$key] = (object) $value;
        }

        return ['type' => 'tagbox', 'variables' => $variables];
    }

}
