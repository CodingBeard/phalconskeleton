<?php

/**
 * Audits
 * 
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

class Audits extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;
     
    /**
     *
     * @var string
     */
    public $modelName;
     
    /**
     *
     * @var integer
     */
    public $row_id;
     
    /**
     *
     * @var integer
     */
    public $user_id;
     
    /**
     *
     * @var string
     */
    public $ip;
     
    /**
     *
     * @var string
     */
    public $type;
     
    /**
     *
     * @var string
     */
    public $date;
     
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->hasMany("id", "Auditfields", "audit_id", ['alias' => 'Auditfields']);
		$this->belongsTo("user_id", "Users", "id", ['alias' => 'Users']);

    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return [
            'id' => 'id', 
            'modelName' => 'modelName', 
            'row_id' => 'row_id', 
            'user_id' => 'user_id', 
            'ip' => 'ip', 
            'type' => 'type', 
            'date' => 'date'
        ];
    }

}
