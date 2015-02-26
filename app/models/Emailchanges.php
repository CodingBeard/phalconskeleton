<?php

/**
 * Email changes
 *
  CREATE TABLE `emailchanges` (
  `id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `user_id` INT NULL DEFAULT NULL,
  `authtoken_id` INT NULL DEFAULT NULL,
  `date` DATETIME NULL DEFAULT NULL,
  `oldEmail` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
  );
  ALTER TABLE `emailchanges` ADD FOREIGN KEY (user_id) REFERENCES `users` (`id`);
  ALTER TABLE `emailchanges` ADD FOREIGN KEY (authtoken_id) REFERENCES `authtokens` (`id`);
 * 
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */
class Emailchanges extends \Phalcon\Mvc\Model
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
    public $user_id;

    /**
     *
     * @var integer
     */
    public $authtoken_id;

    /**
     *
     * @var string
     */
    public $date;

    /**
     *
     * @var string
     */
    public $oldEmail;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->keepSnapshots(true);
        $this->addBehavior(new \Blameable());
        $this->useDynamicUpdate(true);
        $this->belongsTo("authtoken_id", "Authtokens", "id", array('alias' => 'Authtokens'));
        $this->belongsTo("user_id", "Users", "id", array('alias' => 'Users'));
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'user_id' => 'user_id',
            'authtoken_id' => 'authtoken_id',
            'date' => 'date',
            'oldEmail' => 'oldEmail'
        );
    }

}
