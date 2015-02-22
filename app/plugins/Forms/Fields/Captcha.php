<?php

/**
 * Captcha
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */

namespace Forms\Fields;

class Captcha extends Field
{

    /**
     * Volt template for this field
     * @var string
     */
    public $template = 'captcha';

    /**
     * Default value for the field
     * @var string
     */
    public $guestsOnly;

    /**
     * Create a captcha field
     * $properties = [
     *  'guestsOnly' => false
     * ]
     * @param array $properties
     */
    public function __construct($properties = ['guestsOnly' => false])
    {
        foreach ($properties as $key => $value) {
            $this->$key = $value;
        }
    }

}
