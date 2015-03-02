<?php

/**
 * Loose Forms
 *
  CREATE TABLE `looseforms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `private` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `qukforms_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
  ) ENGINE=InnoDB
 * 
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */
class Looseforms extends \Phalcon\Mvc\Model
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
    public $name;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     * 
     * @var integer
     */
    public $private;

    /**
     * Contains array of field [Keys] => full field names
     * @var array
     */
    public $fields = [];

    /**
     * Populate $this->fields with the fields this form has after fetching data
     */
    public function afterFetch()
    {
        foreach ($this->formfields as $field) {
            $this->fields[$field->fieldKey] = $field->fieldName;
        }
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->keepSnapshots(true);
        $this->addBehavior(new \Blameable());
        $this->useDynamicUpdate(true);
        $this->hasMany("id", "Formentrys", "form_id", ['alias' => 'Entries']);
        $this->hasMany("id", "Formfields", "form_id", ['alias' => 'Formfields']);
        $this->belongsTo("user_id", "Users", "id", ['alias' => 'Users']);
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return [
            'id' => 'id',
            'name' => 'name',
            'user_id' => 'user_id',
            'private' => 'private'
        ];
    }

}
