<?php

/**
 * Forms Field Base
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */

namespace Forms\Fields;

class Field
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
     * Regex pattern to match against
     * @var bool|string
     */
    public $pattern = false;

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

    /**
     * Validate the post data for this field
     * @param type $POST
     * @return boolean
     */
    public function validate($POST)
    {
        if ($this->required) {
            if (!strlen(trim($POST[$this->key]))) {
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
