<?php

/**
 * Form Fields
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */
class Formfields extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $form_id;

    /**
     *
     * @var string
     */
    public $fieldKey;

    /**
     *
     * @var string
     */
    public $fieldName;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->keepSnapshots(true);
        $this->addBehavior(new \Blameable());
        $this->useDynamicUpdate(true);
        $this->hasMany("id", "Formdatas", "field_id", ['alias' => 'Formdatas']);
        $this->belongsTo("form_id", "Qukforms", "id", ['alias' => 'Qukforms']);
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return [
            'id' => 'id',
            'form_id' => 'form_id',
            'fieldKey' => 'fieldKey',
            'fieldName' => 'fieldName'
        ];
    }

}
