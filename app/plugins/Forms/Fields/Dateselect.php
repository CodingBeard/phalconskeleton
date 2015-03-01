<?php

/**
 * Dateselect
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */

namespace Forms\Fields;

class Dateselect extends Field
{

    /**
     * Volt template for this field
     * @var string
     */
    public $template = 'dateselect';

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
     * Ranges of the year, month, day to display
     * @var array
     */
    public $ranges = false;

    /**
     * Date string in format: Y-m-d
     * @var string
     */
    public $default = '';

    /**
     * Create a select dropdown for date
     * $properties = [
     *  'key' => '',
     *  'label' => '',
     *  'sublabel' => '',
     *  'required' => false,
     *  'class' => '',
     *  'size' => 12,
     *  'ranges' => [
     *      'year' => range(1900, date('Y')), 'month' => range(1, 12), 'day' => range(1, 31)
     *  ]
     * ]
     * @param array $properties
     */
    public function __construct($properties)
    {
        parent::__construct($properties);

        if (!$this->ranges) {
            $this->ranges['year'] = range(date('Y'), 1900);
            $this->ranges['month'] = range(1, 12);
            $this->ranges['day'] = range(1, 31);
        }

        $years = [];
        foreach ($this->ranges['year'] as $year) {
            $years[] = (object) ['value' => $year, 'default' => false];
        }
        $this->ranges['year'] = $years;
        $months = [];
        foreach ($this->ranges['month'] as $month) {
            if ($month < 10) {
                $month = str_pad($month, 2, '0', STR_PAD_LEFT);
            }
            $months[] = (object) ['value' => $month, 'default' => false];
        }
        $this->ranges['month'] = $months;
        $days = [];
        foreach ($this->ranges['day'] as $day) {
            if ($day < 10) {
                $day = str_pad($day, 2, '0', STR_PAD_LEFT);
            }
            $days[] = (object) ['value' => $day, 'default' => false];
        }
        $this->ranges['day'] = $days;
        $this->ranges = (object) $this->ranges;

        if ($this->default) {
            $this->setDefault($this->default);
        }
    }

    /**
     * Set the default date from the string given
     * @param type $dateString
     */
    public function setDefault($dateString)
    {
        $date = \DateTime::createFromFormat('Y-m-d', $dateString);
        if ($date) {
            foreach ($this->ranges->year as $key => $option) {
                if ($option->value == $date->format('Y')) {
                    $this->ranges->year[$key]->default = 'selected';
                }
            }
            foreach ($this->ranges->month as $key => $option) {
                if ($option->value == $date->format('m')) {
                    $this->ranges->month[$key]->default = 'selected';
                }
            }
            foreach ($this->ranges->day as $key => $option) {
                if ($option->value == $date->format('d')) {
                    $this->ranges->day[$key]->default = 'selected';
                }
            }
        }
    }

    /**
     * Check a valid date was given, concat the three seperate fields into the given main key
     * @param type $POST
     * @return boolean
     */
    public function validate($POST)
    {
        if (strlen($POST[$this->key . '-year'] . $POST[$this->key . '-month'] . $POST[$this->key . '-day'])) {
            $date = \DateTime::createFromFormat('Ymd', $POST[$this->key . '-year'] . $POST[$this->key . '-month'] . $POST[$this->key . '-day']);
            if ($date) {
                $_POST[$this->key] = $date->format('Y-m-d');
                $POST = $_POST;
            }
            else {
                $this->errorMessage = 'That date is not valid';
                return false;
            }
        }
        if (is_callable($this->required)) {
            $this->required = $this->required($POST);
        }
        if ($this->required) {
            if (!strlen(trim($POST[$this->key]))) {
                $this->errorMessage = 'Field is required';
                return false;
            }
        }
        return true;
    }

}
