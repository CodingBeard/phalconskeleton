<?php

/**
 * Forms Field Base
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace CodingBeard\Forms\Fields;

use CodingBeard\Forms\FormBuilder;

class Field extends FormBuilder
{

    /**
     * Error Message
     * @var string
     */
    public $errorMessage = '';

    /**
     * Whether the field is required or not
     * @var bool
     */
    public $required = false;

    /**
     * Text for the required atribute
     * @var bool
     */
    public $requiredAttribute = '';

    /**
     * Regex pattern to match against
     * @var bool|string
     */
    public $pattern = false;

    /**
     * Text for the pattern atribute
     * @var bool|string
     */
    public $patternAttribute = '';

    /**
     * Array of New Model, field to check
     * @var bool|array
     */
    public $unique = false;

    /**
     * Array of New Model, field to check
     * @var bool|array
     */
    public $exists = false;

    /**
     * Whether this field is a repeated field
     * @var bool
     */
    public $isRepeat;

    public function __construct($properties)
    {
        foreach ($properties as $key => $value) {
            if (is_callable($value) && $value instanceof \Closure) {
                $value = $value();
            }
            $this->$key = $value;
        }
        if ($this->required) {
            $this->requiredAttribute = 'required';
        }
        if ($this->pattern) {
            $this->patternAttribute = 'pattern="' . $this->pattern . '"';
        }
    }

    /**
     * Validate the post data for this field
     * @param type $POST
     * @return boolean
     */
    public function validate($POST)
    {
        if ($this->required) {
            if (isset($POST[$this->key]) && !strlen(trim($POST[$this->key]))) {
                $this->errorMessage = 'Field is required';
                return false;
            }
        }
        if ($this->pattern) {
            if (!preg_match("#{$this->pattern}#is", $POST[$this->key])) {
                $this->errorMessage = 'Does not match rules';
                return false;
            }
        }
        if ($this->isRepeat) {
            if ($POST[$this->key] != $POST[substr($this->key, 6)]) {
                $this->errorMessage = 'Field must match "' . substr($this->label, 7) . '"';
                return false;
            }
        }
        if ($this->unique) {
            $model = $this->unique['model'];
            $found = $model::findFirst([
                $this->unique['field'] . ' = :a:',
                'bind' => ['a' => $POST[$this->key]]
            ]);
            if ($found) {
                if (isset($this->unique['message'])) {
                    $this->errorMessage = $this->unique['message'];
                }
                else {
                    $this->errorMessage = 'An entry with this value exists';
                }
                return false;
            }
        }
        if ($this->exists) {
            $model = $this->exists['model'];
            $found = $model::findFirst([
                $this->exists['field'] . ' = :a:',
                'bind' => ['a' => $POST[$this->key]]
            ]);
            if (!$found) {
                if (isset($this->exists['message'])) {
                    $this->errorMessage = $this->exists['message'];
                }
                else {
                    $this->errorMessage = 'This must match an existing entry';
                }
                return false;
            }
        }
        return true;
    }

    /**
     * Set the default value of a field
     */
    public function setDefault()
    {
        
    }

}
