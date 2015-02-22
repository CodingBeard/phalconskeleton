<?php

/**
 * Tagbox
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */

namespace Forms\Fields;

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
    public $tagValues = array();

    /**
     * Array of the labels for auto-complete
     * @var array
     */
    public $tagLabels = array();

    /**
     * Create a select dropdown
     * $properties = [
     *  'key' => '',
     *  'label' => '',
     *  'sublabel' => '',
     *  'required' => false,
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
            $this->tagValues[$option->label] = $option->value;
            $this->tagLabels[] = $option->label;
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

}
