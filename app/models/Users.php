<?php

/**
 * Users
 *
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(255) DEFAULT NULL,
  `lastName` varchar(255) DEFAULT NULL,
  `DoB` date DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `card` tinyint(4) DEFAULT NULL,
  `active` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB
 * 
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */
class Users extends \Phalcon\Mvc\Model
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
    public $firstName;

    /**
     *
     * @var string
     */
    public $lastName;

    /**
     *
     * @var string
     */
    public $DoB;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var integer
     */
    public $active;

    /**
     * Escape and concat first/last name
     * @return string
     */
    public function getName()
    {
        return $this->getDI()->get('escaper')->escapeHtml($this->firstName) . ' ' . $this->getDI()->get('escaper')->escapeHtml($this->lastName);
    }
    
    /**
     * Add a role to the user
     * @param string $roleName
     */
    public function addRole($roleName)
    {
        if (!$this->hasRole($roleName)) {
            $role = \Roles::findFirst([
                'name = :a:',
                'bind' => ['a' => $roleName]
            ]);
            if ($role) {
                $userrole = new Userroles();
                $userrole->user_id = $this->id;
                $userrole->role_id = $role->id;
                $userrole->save();
            }
        }
    }

    /**
     * Remove a rold from the user
     * @param string $roleName
     * @return boolean
     */
    public function removeRole($roleName)
    {
        $role = $this->hasRole($roleName);
        if ($role) {
            return $role->delete();
        }
        return false;
    }

    /**
     * Check for the existance of a userrole
     * @param string $roleName
     * @return boolean
     */
    public function hasRole($roleName)
    {
        $role = \Roles::findFirst([
            'name = :a:',
            'bind' => ['a' => $roleName]
        ]);
        if ($role) {
            return $this->getUserroles([
                'role_id = :a:',
                'bind' => ['a' => $role->id]
            ])->getFirst();
        }
        return false;
    }

    /**
     * Returns all users with a specific role
     * @param string $roleName
     * @return 
     */
    public static function getUsersByRole($roleName)
    {
        $role = \Roles::findFirst([
            'name = :a:',
            'bind' => ['a' => $roleName]
        ]);
        if ($role) {
            return $role->users;
        }
    }

    /**
     * Initialize model
     */
    public function initialize()
    {
        $this->keepSnapshots(true);
        $this->addBehavior(new \Blameable());
        $this->useDynamicUpdate(true);
        $this->hasManyToMany(
        "id", "Userroles", "user_id", "role_id", "Roles", "id", ['alias' => 'Roles']
        );
        $this->hasMany("id", "Userroles", "user_id", ['alias' => 'Userroles']);
        $this->hasMany("id", "Accountactivations", "user_id", ['alias' => 'Accountactivations']);
        $this->hasMany("id", "Audits", "user_id", ['alias' => 'Audits']);
        $this->hasMany("id", "Cookietokens", "user_id", ['alias' => 'Cookietokens']);
    }

    /**
     * Hash the password
     */
    public function hashPass()
    {
        if (strlen($this->password) >= 8) {
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        }
    }

    /**
     * Creates a remember me cookie - used with \Auth
     * @return array
     */
    public function createCookieToken()
    {
        \Cookietokens::find([
            'user_id = :a:',
            'bind' => ['a' => $this->id]
        ])->delete();

        $token = \Phalcon\Text::random(\Phalcon\Text::RANDOM_ALNUM, 20);

        $cookie = new Cookietokens();
        $cookie->user_id = $this->id;
        $cookie->token = password_hash($token, PASSWORD_DEFAULT);
        $cookie->save();
        return ['user_id' => $this->id, 'token' => $token];
    }

    /**
     * Column Map
     * @return array
     */
    public function columnMap()
    {
        return [
            'id' => 'id',
            'firstName' => 'firstName',
            'lastName' => 'lastName',
            'DoB' => 'DoB',
            'email' => 'email',
            'password' => 'password',
            'active' => 'active'
        ];
    }

}
