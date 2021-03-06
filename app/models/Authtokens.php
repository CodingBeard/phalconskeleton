<?php

/**
 * Authtokens
 * 
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace models;

use CodingBeard\Blameable;
use Phalcon\Mvc\Model;
use Phalcon\Text;

class Authtokens extends Model
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
    public $tokenKey;

    /**
     *
     * @var string
     */
    public $token;

    /**
     * Plaintext version of the token
     * @var string
     */
    public $string;

    /**
     * Create an authtoken object and return it
     * $properties = ['user_id' => 1, 'type' => '', 'unique' => false, 'expires' => 1]
     * @param array $properties
     * @return Authtokens
     */
    public static function newToken($properties)
    {
        if (!isset($properties['expires']))
            $properties['expires'] = 1;

        if ($properties['unique']) {
            $tokens = Authtokens::find([
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

        $string = Text::random(Text::RANDOM_ALNUM, 20);

        $authtoken = new Authtokens();
        $authtoken->user_id = $properties['user_id'];
        $authtoken->issued = date('Y-m-d H:i:s');
        $authtoken->expires = date('Y-m-d H:i:s', time() + (60 * 60 * 24 * $properties['expires']));
        $authtoken->type = $properties['type'];
        $authtoken->tokenKey = substr($string, 0, 10);
        $authtoken->token = substr($string, 10);
        $authtoken->hashToken();
        $authtoken->save();

        $authtoken->string = $string;

        return $authtoken;
    }

    /**
     * Hash the token
     */
    public function hashToken()
    {
        $this->token = password_hash($this->token, PASSWORD_DEFAULT);
    }

    public function expire()
    {
        $this->expires = date('Y-m-d H:i:s', time() - 3600);
    }

    /**
     * Check the validity of a token
     * @param string $type
     * @param string $token
     * @return bool|int
     */
    public static function checkToken($type, $token, $user_id = false)
    {
        $authtoken = Authtokens::findFirst([
            'type = :a: AND tokenKey = :b: AND expires >= :c:',
            'bind' => [
                'a' => $type,
                'b' => substr($token, 0, 10),
                'c' => date('Y-m-d H:i:s')
            ]
        ]);
        if (!$authtoken) {
            return false;
        }
        if (!password_verify(substr($token, 10), $authtoken->token)) {
            $authtoken->expire();
            return false;
        }
        $authtoken->expire();
        if ($user_id) {
            return $authtoken->users->id;
        }
        else {
            return $authtoken;
        }
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->keepSnapshots(true);
        $this->addBehavior(new Blameable());
        $this->useDynamicUpdate(true);
        $this->hasMany('id', 'models\Emailchanges', 'authtoken_id', ['alias' => 'Emailchanges']);
        $this->belongsTo('user_id', 'models\Users', 'id', ['alias' => 'Users']);
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return [
            'id' => 'id',
            'user_id' => 'user_id',
            'issued' => 'issued',
            'expires' => 'expires',
            'type' => 'type',
            'tokenKey' => 'tokenKey',
            'token' => 'token'
        ];
    }

}
