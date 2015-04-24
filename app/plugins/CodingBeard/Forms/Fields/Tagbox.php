<?php

/**
 * Tagbox
 *
 * @category
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace CodingBeard\Forms\Fields;

use CodingBeard\Forms\Fields\Field;

class Tagbox extends Field
{

    /**
     * Volt template for this field
     * @var string
     */
    public $template = 'tagbox';

    /**
     * Field key
     * @var string
     */
    public $key = 'key';

    /**
     * Field Label
     * @var string
     */
    public $label = '';

    /**
     * Field Sublabel
     * @var string
     */
    public $sublabel = '';

    /**
     * Whether the field is required or not
     * @var bool
     */
    public $required = false;

    /**
     * Classes to be added to the input tag
     * @var string
     */
    public $class = '';

    /**
     * Ratio of element size on a large screen (out fo 12)
     * @var int
     */
    public $size = 12;

    /**
     * Minimum characters typed before autocomplete popup
     * @var string
     */
    public $autocompleteOnFocus = 'false';

    /**
     * Minimum characters typed before autocomplete popup
     * @var int
     */
    public $minLength = 2;

    /**
     * A limit on the amount of tags possible to add
     * @var string|int
     */
    public $tagLimit = 'null';

    /**
     * Options for the auto-complete
     * @var string
     */
    public $options;

    /**
     * Array of the labels:values
     * @var array
     */
    public $tagValues = [];

    /**
     * Array of the labels for auto-complete
     * @var array
     */
    public $tagLabels = [];

    /**
     * Create a select dropdown
     * $properties = [
     *  'key' => '',
     *  'label' => '',
     *  'sublabel' => '',
     *  'required' => false,
     *  'autocompleteOnFocus' => false,
     *  'class' => '',
     *  'size' => 12,
     *  'tagLimit' => 'null',
     *  'options' => [
     *      ['value' => '', 'label' => '', 'default' => false]
     *  ]
     * ]
     * @param array $properties
     */
    public function __construct($properties)
    {
        parent::__construct($properties);
        foreach ($this->options as $key => $option) {
            $this->options[$key] = (object)$option;
        }
        foreach ($this->options as $key => $option) {
            $this->tagValues[$option->label] = $option->value;
            $this->tagLabels[] = $option->label;
        }

        if ($this->autocompleteOnFocus) {
            $this->autocompleteOnFocus = 'true';
            $this->minLength = 0;
        }
    }

    public function setDefault($value)
    {
        foreach ($this->options as $key => $option) {
            if ($option->value == $value) {
                $this->options[$key]->default = true;
            }
        }
    }

    /**
     * Validate the post data for this field
     * @param type $POST
     * @return boolean
     */
    public function validate($POST)
    {
        if (is_callable($this->required)) {
            $this->required = $this->required($POST);
        }
        if ($this->required) {
            if (!count($POST[$this->key])) {
                $this->errorMessage = 'Field is required';

                return false;
            }
        }

        return true;
    }

}
