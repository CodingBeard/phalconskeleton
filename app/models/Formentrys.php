<?php

/**
 * Form Entries
 *
  CREATE TABLE `formentrys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `form_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `form_id` (`form_id`),
  CONSTRAINT `formentrys_ibfk_2` FOREIGN KEY (`form_id`) REFERENCES `looseforms` (`id`),
  CONSTRAINT `formentrys_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
  ) ENGINE=InnoDB
 * 
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */
class Formentrys extends \Phalcon\Mvc\Model
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
    public $date;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var integer
     */
    public $form_id;

    /**
     * Contains array of field [Keys] => full field names
     * @var array
     */
    public $fields = [];

    /**
     * Returns the data set to the key privided
     * @param string $key
     * @return string
     */
    public function getField($key)
    {
        return $this->$key;
    }

    /**
     * Populate $this->fields with the fields this form has after fetching data,
     * Populates $this->$variableKey with data from this form's entry
     */
    public function afterFetch()
    {
        foreach ($this->formdatas as $formdata) {
            $field = $formdata->formfields;
            $this->fields[$field->fieldKey] = $field->fieldName;

            $key = $field->fieldKey;
            $this->$key = $formdata->value;
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
        $this->hasMany("id", "Formdatas", "formentry_id", ['alias' => 'Formdatas']);
        $this->belongsTo("form_id", "Qukforms", "id", ['alias' => 'Qukforms']);
        $this->belongsTo("user_id", "Users", "id", ['alias' => 'Users']);
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return [
            'id' => 'id',
            'date' => 'date',
            'user_id' => 'user_id',
            'form_id' => 'form_id'
        ];
    }

}
