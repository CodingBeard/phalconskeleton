<?php

/**
 * Auditfields
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */

class Auditfields extends \Phalcon\Mvc\Model
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
    public $audit_id;
     
    /**
     *
     * @var string
     */
    public $fieldName;
     
    /**
     *
     * @var string
     */
    public $oldValue;
     
    /**
     *
     * @var string
     */
    public $newValue;
     
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->belongsTo("audit_id", "Audits", "id", ['alias' => 'Audits']);

    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return [
            'id' => 'id', 
            'audit_id' => 'audit_id', 
            'fieldName' => 'fieldName', 
            'oldValue' => 'oldValue', 
            'newValue' => 'newValue'
        ];
    }

}
