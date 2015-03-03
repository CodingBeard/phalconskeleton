<?php

/**
 * Checkbox
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace CodingBeard\Forms\Fields;

use CodingBeard\Forms\Fields\Field;

class Checkbox extends Field
{

    /**
     * Volt template for this field
     * @var string
     */
    public $template = 'checkbox';

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
     * Default value for the field
     * @var bool
     */
    public $default;

    /**
     * Create a textbox field
     * $properties = [
     *  'key' => '',
     *  'label' => '',
     *  'sublabel' => '',
     *  'required' => false,
     *  'class' => '',
     *  'size' => 12,
     *  'default' => ''
     * ]
     * @param array $properties
     */
    public function __construct($properties)
    {
        parent::__construct($properties);
        if ($this->default) {
            $this->default = 'checked';
        }
    }

    public function setDefault($value)
    {
        if ($value) {
            $this->default = 'checked';
        }
    }

}
