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
     * Set the roles for a user
     * @param array $roleNames
     */
    public function setRoles($roleNames)
    {
        $int = false;
        if (is_int(abs($roleNames[0]))) {
            $int = true;
        }

        foreach ($roleNames as $roleName) {
            if (!$this->hasRole($roleName)) {
                $this->addRole($roleName);
            }
        }

        foreach ($this->roles as $role) {
            if ($role->id == 1 && $this->id == $this->getDI()->get('auth')->audit_id) {
                $this->getDI()->get('flashSession')->error('You cannot remove Root Admin from yourself');
                continue;
            }
            if ($int) {
                if (!in_array($role->id, $roleNames)) {
                    $this->removeRole($role->id);
                }
            }
            else {
                if (!in_array($role->name, $roleNames)) {
                    $this->removeRole($role->name);
                }
            }
        }
    }

    /**
     * Add a role to the user
     * @param string|int $roleName
     */
    public function addRole($roleName)
    {
        if ($this->hasRole($roleName)) {
            return false;
        }

        if (is_int(abs($roleName))) {
            $role = \Roles::findFirstById($roleName);
        }
        else {
            $role = \Roles::findFirstByName($roleName);
        }

        if (!$role) {
            return false;
        }

        $userrole = new Userroles();
        $userrole->user_id = $this->id;
        $userrole->role_id = $role->id;
        $userrole->save();
    }

    /**
     * Remove a rold from the user
     * @param string $roleName
     * @return boolean
     */
    public function removeRole($roleName)
    {
        $role = $this->hasRole($roleName);
        if (!$role) {
            return false;
        }
        return $role->delete();
    }

    /**
     * Check for the existance of a userrole
     * @param string $roleName
     * @return boolean
     */
    public function hasRole($roleName)
    {
        if (is_int(abs($roleName))) {
            $role = \Roles::findFirstById($roleName);
        }
        else {
            $role = \Roles::findFirstByName($roleName);
        }

        if (!$role) {
            return false;
        }

        return $this->getUserroles([
            'role_id = :a:',
            'bind' => ['a' => $role->id]
        ])->getFirst();
    }

    /**
     * Returns all users with a specific role
     * @param string $roleName
     * @return 
     */
    public static function getUsersByRole($roleName)
    {
        if (is_int(abs($roleName))) {
            $role = \Roles::findFirstById($roleName);
        }
        else {
            $role = \Roles::findFirstByName($roleName);
        }

        if (!$role) {
            return false;
        }

        return $role->users;
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
        $this->hasMany("id", "Emailchanges", "user_id", ['alias' => 'Emailchanges']);
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
