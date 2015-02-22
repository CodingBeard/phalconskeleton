<?php

/**
 * Group of checkboxes
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */

namespace Forms\Fields;

class Checkboxgroup extends Field
{

    /**
     * Volt template for this field
     * @var string
     */
    public $template = 'checkboxgroup';

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
     * Options for the group of checkboxes
     * @var string
     */
    public $options;

    /**
     * Create a group of checkboxes
     * $properties = [
     *  'label' => '',
     *  'sublabel' => '',
     *  'required' => false,
     *  'class' => '',
     *  'size' => 12,
     *  'options' => [
     *      ['key' => '', 'label' => '', 'default' => false]
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
        foreach ($this->options as $key => $option) {
            $this->options[$key] = (object) $option;
        }
        foreach ($this->options as $key => $option) {
            if ($option->default) {
                $this->setDefault($option->key, $option->default);
            }
        }
    }

    public function setDefault($key, $value)
    {
        foreach ($this->options as $optionkey => $option) {
            if ($option->key == $key) {
                if ($value) {
                    $this->options[$optionkey]->default = 'checked';
                }
            }
        }
    }

}