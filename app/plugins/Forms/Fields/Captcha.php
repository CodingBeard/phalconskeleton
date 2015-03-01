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
     * Ratio of element size on a large screen (out fo 12)
     * @var int
     */
    public $size = 12;

    /**
     * Create a captcha field
     * $properties = [
     *  'guestsOnly' => false
     * ]
     * @param array $properties
     */
    public function __construct($properties = ['guestsOnly' => false])
    {
        parent::__construct($properties);
    }

}
