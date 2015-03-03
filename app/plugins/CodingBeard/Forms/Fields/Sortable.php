<?php

/**
 * Sortable
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace CodingBeard\Forms\Fields;

use CodingBeard\Forms\Fields\Field;

class Sortable extends Field
{

    /**
     * Volt template for this field
     * @var string
     */
    public $template = 'sortable';

    /**
     * Field key
     * @var string
     */
    public $key = 'key';

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
     * Row headers
     * @var array
     */
    public $headers;

    /**
     * Input names for each row
     * @var array
     */
    public $names;

    /**
     * Default values
     * @var string
     */
    public $options;

    /**
     * The default field types for a new row
     * @var array
     */
    public $newRow;

    /**
     * String of a new row
     * @var array
     */
    public $newTextRow;

    /**
     * Create a select dropdown
     * $properties = [
     *  'key' => '',
     *  'class' => '',
     *  'size' => 12,
     *  'headers' => ['Name 1', 'Name 2'],
     *  'options' => [
     *      [
     *          new \Forms\Fields\Textbox(['key' => 'name1']),
     *          new \Forms\Fields\Textbox(['key' => 'name2']),
     *      ]
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
        foreach ($this->options as $rowkey => $row) {
            foreach ($row as $fieldkey => $field) {
                
                /**
                 * First set of fields, set up defaults
                 */
                if ($rowkey == 0) {
                    //Unaltered post keys
                    $this->names[] = $field->key;
                    //Enaltered fields with no value to generate html for a new blank row
                    $new = clone $field;
                    $new->setDefault('');
                    $this->newRow[] = $new;
                }
                //Set the class for the jquery sortable functionality
                $field->class = $field->key;
                //Change the fields to an array format
                $field->key = "{$this->key}[$rowkey][$field->key]";
                $this->options[$rowkey][$fieldkey] = $field;
            }
        }
        
        /**
         * Generate html for a blank row
         */
        $rowText = '<tr>';
        foreach ($this->newRow as $field) {
            $field = clone $field;
            $field->class = $field->key;
            $field->key = "{$this->key}[_ROWID_][$field->key]";
            $rowText .= '<td>' . addslashes(preg_replace('#[\n|\r|\n\r]#is', '', $this->renderField($field))) . '</td>';
        }
        $this->newTextRow = $rowText . '<td><a class=\"btn btn-small red remove-row\" href=\"#\">Delete</a></td><td><i class=\"mdi-navigation-unfold-more\"></i></td></tr>';
    }

    /**
     * Goes through every generated field in the table #fields/row * rows
     * and checks their validity using their own validators
     * @param array $POST
     * @return boolean
     */
    public function validate($POST)
    {
        $valid = true;
        foreach ($this->options as $rowKey => $row) {
            foreach ($this->names as $fieldKey => $fieldName) {
                /**
                 * Get the field we want to validate, it doesn't look through
                 * $_POST['fields'], because some fields don't send values if
                 * they aren't selected, E.G. checkboxes.
                 */
                $field = $this->options[$rowKey][$fieldKey];
                
                /**
                 * Create a 'fake' POST array to hand to the individual validator
                 * so when it tries to access the array post field we set in the
                 * constructor E.G. $_POST['name[0][key]']; it will find it correctly.
                 */
                $vanillaPost = [$row[$fieldKey]->key => $POST[$this->key][$rowKey][$fieldName]];
                
                /**
                 * Validate the field
                 */
                if (!$field->validate($vanillaPost)) {
                    $valid = false;
                }
            }
        }
        return $valid;
    }

    /**
     * Set the default values in each field in each row, requires an array of E.G.
     * keyName[$rowId][$fieldName] = $value;
     * @param array $rows
     */
    public function setDefault($rows)
    {
        $options = [];
        foreach ($rows as $rowKey => $row) {
            foreach ($this->newRow as $fieldKey => $field) {
                /**
                 * Get a blank field from the new row, it doesn't look through
                 * $_POST['fields'], because some fields don't send values if
                 * they aren't selected, E.G. checkboxes.
                 * Clone it and then set the field's value and error message
                 * if any
                 */
                $copyfield = clone $field;
                $copyfield->setDefault($row[$field->key]);
                $copyfield->errorMessage = $this->options[$rowKey][$fieldKey]->errorMessage;
                $options[$rowKey][$field->key] = $copyfield;
            }
        }
        $this->options = $options;
        
        
        /**
         * Same logic we used in the second part of the constructor
         */
        foreach ($this->options as $rowkey => $row) {
            foreach ($row as $fieldkey => $field) {
                if ($rowkey == 0) {
                    $this->names[] = $field->key;
                }
                $field->class = $field->key;
                $field->key = "{$this->key}[$rowkey][$field->key]";
                $this->options[$rowkey][$fieldkey] = $field;
            }
        }
    }

}
