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
        $this->hasMany("id", "Usertokens", "user_id", ['alias' => 'Usertokens']);
        $this->hasMany("id", "Audits", "user_id", ['alias' => 'Audits']);
        $this->hasMany("id", "Logins", "user_id", ['alias' => 'Logins']);
    }

    /**
     * Hash the password
     */
    public function hashPass()
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
    }

    /**
     * Check the supplied password
     * @param string $password
     * @return bool
     */
    public function checkPass($password)
    {
        return password_verify($password, $this->password);
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
