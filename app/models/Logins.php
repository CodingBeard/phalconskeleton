<?php

/**
 * Logins
 *
  CREATE TABLE `logins` (
  `id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `user_id` INT NULL DEFAULT NULL,
  `ip` VARCHAR(255) NULL DEFAULT NULL,
  `attempt` INT(11) NULL DEFAULT NULL,
  `success` TINYINT NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
  );
  ALTER TABLE `logins` ADD FOREIGN KEY (user_id) REFERENCES `users` (`id`);
 * 
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */
class Logins extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $ip;

    /**
     *
     * @var integer
     */
    public $attempt;

    /**
     *
     * @var integer
     */
    public $success;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->keepSnapshots(true);
        $this->addBehavior(new \Blameable());
        $this->useDynamicUpdate(true);
        $this->belongsTo('user_id', 'Users', 'id', array('alias' => 'Users'));
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'user_id' => 'user_id',
            'ip' => 'ip',
            'attempt' => 'attempt',
            'success' => 'success'
        );
    }

}
