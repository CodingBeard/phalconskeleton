<?php

/**
 * Hidden
 *
 * @category
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace CodingBeard\Forms\Fields;

class Freetext extends Field
{

    /**
     * Volt template for this field
     * @var string
     */
    public $template = 'freetext';

    /**
     * Default value for the field
     * @var string
     */
    public $text;

    /**
     * Field key
     * @var string
     */
    public $key;

    /**
     * 100% width
     * @var int
     */
    public $size = 12;

    /**
     * 0/12 offset
     * @var int
     */
    public $offset = 0;

    /**
     * Default value for the field
     * @var string
     */
    public $default;

    /**
     * Add text to a form
     * $properties = [
     *  'size' => 12,
     *  'offset' => 0,
     *  'text' => 'text to use',
     * ]
     * or
     * $properties = 'text to use'
     * @param array $properties
     */
    public function __construct($properties)
    {
        if (is_array($properties)) {
            parent::__construct($properties);
        }
        else {
            $this->text = $properties;
        }
    }

    public function setDefault($value)
    {
        return true;
    }

    public function validate($POST)
    {
        return true;
    }

}
