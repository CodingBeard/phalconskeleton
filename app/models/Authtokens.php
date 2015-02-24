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
     * Create an authtoken object and return it
     * $properties = ['user_id' => 1, 'type' => '', 'unique' => false, 'expires' => 1, 'length' => 20]
     * @param array $properties
     * @return \Authtokens
     */
    public static function newToken($properties)
    {
        if (!isset($properties['expires']))
            $properties['expires'] = 1;
        if (!isset($properties['length']))
            $properties['length'] = 20;
        
        if ($properties['unique']) {
            $tokens = \Authtokens::find([
                'user_id = :a: AND type = :b:',
                'bind' => ['a' => $properties['user_id'], 'b' => $properties['type']]
            ]);
            if ($tokens) {
                foreach ($tokens as $token) {
                    $token->expires = date('Y-m-d H:i:s');
                    $token->save();
                }
            }
        }
        
        $token = \Phalcon\Text::random(\Phalcon\Text::RANDOM_ALNUM, $properties['length']);
        
        $authtoken = new \Authtokens();
        $authtoken->user_id = $properties['user_id'];
        $authtoken->issued = date('Y-m-d H:i:s');
        $authtoken->expires = date('Y-m-d H:i:s', time() + (60 * 60 * 24 * $properties['expires']));
        $authtoken->type = $properties['type'];
        $authtoken->token = $token;
        $authtoken->hashToken();
        $authtoken->save();
        
        return $token;
    }
    
    /**
     * Hash the token
     */
    public function hashToken()
    {
        $this->token = password_hash($this->token, PASSWORD_DEFAULT);
    }

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
