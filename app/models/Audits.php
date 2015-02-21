<?php

/**
 * Audits
 *
CREATE TABLE `audits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modelName` varchar(255) DEFAULT NULL,
  `row_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `type` char(2) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `audits_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB
 * 
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
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
    
    public function beforeDelete()
    {
        if ($this->auditfields) {
            $this->auditfields->delete();
        }
    }
     
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
