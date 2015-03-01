<?php

/**
 * Hidden
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */

namespace Forms\Fields;

class Hidden extends Field
{

    /**
     * Volt template for this field
     * @var string
     */
    public $template = 'hidden';

    /**
     * Field key
     * @var string
     */
    public $key = 'key';
    
    /**
     * 0 width
     * @var int
     */
    public $size = 0;

    /**
     * Default value for the field
     * @var string
     */
    public $default;

    /**
     * Create a textbox field
     * $properties = [
     *  'key' => '',
     *  'default' => ''
     * ]
     * @param array $properties
     */
    public function __construct($properties)
    {
        parent::__construct($properties);
    }

    public function setDefault($value)
    {
        $this->default = $value;
    }

}
