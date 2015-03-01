<?php

/**
 * Permissions
 * 
  CREATE TABLE `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(255) DEFAULT NULL,
  `controller` varchar(255) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
  ) ENGINE=InnoDB
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */
class Permissions extends \Phalcon\Mvc\Model
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
    public $module;

    /**
     *
     * @var string
     */
    public $controller;

    /**
     *
     * @var string
     */
    public $action;

    /**
     * Set the roles which can access a page
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
        if ($this->roles->count()) {
            foreach ($this->roles as $role) {
                if (!in_array($role->id, $roleNames)) {
                    $this->removeRole($role->id);
                }
            }
        }
        return true;
    }

    /**
     * Add a role which can access a page
     * @param string|int $role_id
     */
    public function addRole($role_id)
    {
        if ($this->hasRole($role_id)) {
            return false;
        }

        $role = \Roles::findFirstById($role_id);

        if (!$role) {
            return false;
        }

        $permissionrole = new Permissionroles();
        $permissionrole->permission_id = $this->id;
        $permissionrole->role_id = $role->id;
        $permissionrole->save();
    }

    /**
     * Remove a role which can access a page
     * @param int $role_id
     * @return boolean
     */
    public function removeRole($role_id)
    {
        $role = $this->hasRole($role_id);
        if (!$role) {
            return false;
        }
        return $role->delete();
    }

    /**
     * Check for the existance of a role which can access this page
     * @param int $role_id
     * @return boolean|\Permissionroles
     */
    public function hasRole($role_id)
    {
        return $this->getPermissionroles([
            'role_id = :a:',
            'bind' => ['a' => $role_id]
        ])->getFirst();
    }

    /**
     * Propagate delete to permissionroles
     */
    public function beforeDelete()
    {
        foreach ($this->permissionroles as $permissionrole) {
            $permissionrole->delete();
        }
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return [
            'id' => 'id',
            'module' => 'module',
            'controller' => 'controller',
            'action' => 'action',
        ];
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->keepSnapshots(true);
        $this->addBehavior(new \Blameable());
        $this->useDynamicUpdate(true);
        $this->hasManyToMany(
        "id", "Permissionroles", "permission_id", "role_id", "Roles", "id"
        );
        $this->hasMany("id", "Permissionroles", "permission_id", ['alias' => 'Permissionroles']);
        $this->hasMany("id", "Navlinks", "permission_id", ['alias' => 'Navlinks']);
    }

}
