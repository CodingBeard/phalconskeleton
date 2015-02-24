<?php

/**
 * Radio
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */

namespace Forms\Fields;

class Radio extends Field
{

    /**
     * Volt template for this field
     * @var string
     */
    public $template = 'radio';

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
     * Options for the set of radio buttons
     * @var string
     */
    public $options;

    /**
     * Create a set of radio buttons
     * $properties = [
     *  'key' => '',
     *  'label' => '',
     *  'sublabel' => '',
     *  'required' => false,
     *  'class' => '',
     *  'size' => 12,
     *  'options' => [
     *      ['value' => '', 'label' => '', 'default' => false]
     *  ]
     * ]
     * @param array $properties
     */
    public function __construct($properties)
    {
        foreach ($properties as $key => $value) {
            if (is_callable($value)) {
                $value = $value();
            }
            $this->$key = $value;
        }
        if ($this->required) {
            $this->requiredAttribute = 'required';
        }
        foreach ($this->options as $key => $option) {
            $this->options[$key] = (object) $option;
        }
        foreach ($this->options as $key => $option) {
            if ($option->default) {
                $this->setDefault($option->value);
            }
        }
    }

    public function setDefault($value)
    {
        foreach ($this->options as $key => $option) {
            if ($option->value == $value) {
                $this->options[$key]->default = 'checked';
            }
        }
    }

}
