<?php

/**
 * Password resets
 *
CREATE TABLE `passresets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `reset` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `passresets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB
 * 
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */

class Passresets extends \Phalcon\Mvc\Model
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
    public $token;
     
    /**
     *
     * @var integer
     */
    public $reset;
     
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->keepSnapshots(true);
		$this->addBehavior(new \Blameable());
        $this->useDynamicUpdate(true);
		$this->belongsTo("user_id", "Users", "id", ['alias' => 'Users']);

    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return [
            'id' => 'id', 
            'user_id' => 'user_id', 
            'token' => 'token', 
            'reset' => 'reset'
        ];
    }

}
