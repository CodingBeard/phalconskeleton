<?php

/**
 * Form Fields
 * 
  CREATE TABLE `formfields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` int(11) DEFAULT NULL,
  `fieldKey` varchar(255) DEFAULT NULL,
  `fieldName` mediumtext,
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`),
  CONSTRAINT `formfields_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `looseforms` (`id`)
  ) ENGINE=InnoDB
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
