<?php

/**
 * Form Data
 * 
  CREATE TABLE `formdatas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `formentry_id` int(11) DEFAULT NULL,
  `field_id` int(11) DEFAULT NULL,
  `value` mediumtext,
  PRIMARY KEY (`id`),
  KEY `formentry_id` (`formentry_id`),
  KEY `field_id` (`field_id`),
  CONSTRAINT `formdatas_ibfk_2` FOREIGN KEY (`field_id`) REFERENCES `formfields` (`id`),
  CONSTRAINT `formdatas_ibfk_1` FOREIGN KEY (`formentry_id`) REFERENCES `formentrys` (`id`)
  ) ENGINE=InnoDB
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */
class Formdatas extends \Phalcon\Mvc\Model
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
    public $formentry_id;

    /**
     *
     * @var integer
     */
    public $field_id;

    /**
     *
     * @var string
     */
    public $value;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->keepSnapshots(true);
        $this->addBehavior(new \Blameable());
        $this->useDynamicUpdate(true);
        $this->belongsTo("field_id", "Formfields", "id", array('alias' => 'Formfields'));
        $this->belongsTo("formentry_id", "Formentrys", "id", array('alias' => 'Formentrys'));
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'formentry_id' => 'formentry_id',
            'field_id' => 'field_id',
            'value' => 'value'
        );
    }

}
