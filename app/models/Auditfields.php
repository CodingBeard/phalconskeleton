<?php

/**
 * Auditfields
 * 
CREATE TABLE `auditfields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `audit_id` int(11) DEFAULT NULL,
  `fieldName` varchar(255) DEFAULT NULL,
  `oldValue` mediumtext,
  `newValue` mediumtext,
  PRIMARY KEY (`id`),
  KEY `audit_id` (`audit_id`),
  CONSTRAINT `auditfields_ibfk_1` FOREIGN KEY (`audit_id`) REFERENCES `audits` (`id`)
) ENGINE=InnoDB
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
