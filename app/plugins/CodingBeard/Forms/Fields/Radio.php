<?php

/**
 * Radio
 *
 * @category
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace CodingBeard\Forms\Fields;

use CodingBeard\Forms\Fields\Field;

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
     * Whether to display the buttons inline or not
     * @var string
     */
    public $inline = false;

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
        parent::__construct($properties);
        if ($this->inline) {
            $this->inline = 'inline';
        }
        foreach ($this->options as $key => $option) {
            $this->options[$key] = (object)$option;
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
