<?php

/**
 * Users
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

class Users extends Model
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
        if (is_array($roleNames)) {
            foreach ($roleNames as $roleName) {
                if (!$this->hasRole($roleName)) {
                    $this->addRole($roleName);
                }
            }
        }
        if (!is_array($roleNames)) {
            $roleNames = [];
        }
        if ($this->roles->count()) {
            foreach ($this->roles as $role) {
                if (!in_array($role->id, $roleNames) || !in_array($role->name, $roleNames)) {
                    if ($role->id == 1 && $this->id == $this->getDI()->get('auth')->audit_id) {
                        $this->getDI()->get('flashSession')->error('You cannot remove Root Admin from yourself');
                        continue;
                    }
                    $this->removeRole($role->id);
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

        $role = Roles::findFirstById($roleName);

        if (!$role) {
            $role = Roles::findFirstByName($roleName);
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
        $role = Roles::findFirstById($roleName);

        if (!$role) {
            $role = Roles::findFirstByName($roleName);
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
        $role = Roles::findFirstById($roleName);

        if (!$role) {
            $role = Roles::findFirstByName($roleName);
        }

        if (!$role) {
            return false;
        }

        return $role->users;
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
     * Initialize model
     */
    public function initialize()
    {
        $this->keepSnapshots(true);
        $this->addBehavior(new Blameable());
        $this->useDynamicUpdate(true);
        $this->hasManyToMany(
            "id", "models\Userroles", "user_id", "role_id", "models\Roles", "id", ['alias' => 'Roles']
        );
        $this->hasMany("id", "models\Userroles", "user_id", ['alias' => 'Userroles']);
        $this->hasMany("id", "models\Usertokens", "user_id", ['alias' => 'Usertokens']);
        $this->hasMany("id", "models\Audits", "user_id", ['alias' => 'Audits']);
        $this->hasMany("id", "models\Logins", "user_id", ['alias' => 'Logins']);
        $this->hasMany("id", "models\Emailchanges", "user_id", ['alias' => 'Emailchanges']);
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
            'password' => 'password'
        ];
    }

    /**
     * To string
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

}
