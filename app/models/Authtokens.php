<?php

/**
 * Authtokens
 *
  CREATE TABLE `authtokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `issued` datetime DEFAULT NULL,
  `expires` datetime DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `authtokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
  ) ENGINE=InnoDB
 * 
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */
class Authtokens extends \Phalcon\Mvc\Model
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
    public $issued;

    /**
     *
     * @var string
     */
    public $expires;

    /**
     *
     * @var string
     */
    public $type;

    /**
     *
     * @var string
     */
    public $token;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo("user_id", "\Models\Users", "id", array('alias' => 'Users'));
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'user_id' => 'user_id',
            'issued' => 'issued',
            'expires' => 'expires',
            'type' => 'type',
            'token' => 'token'
        );
    }

}
