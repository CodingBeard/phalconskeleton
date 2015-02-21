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
            "id",
            "Permissionroles",
            "permission_id", "role_id",
            "Roles",
            "id"
        );
        $this->hasMany("id", "Permissionroles", "permission_id", ['alias' => 'Permissionroles']);
    }

    /**
     * Updates the permissions in database
     */
    public static function addNewActions()
    {
        $modules = self::getControllersActions();
        $inDb = [];
        foreach (\Permissions::find() as $permission) {
            $inDb[$permission->module . $permission->controller . $permission->action] = $permission;
        }
        $inFolders = [];
        foreach ($modules as $module => $controllers) {
            foreach ($controllers as $controller => $actions) {
                foreach ($actions as $action) {
                    $inFolders[$module . $controller . $action] = $module . $controller . $action;
                    if (!array_key_exists($module . $controller . $action, $inDb)) {
                        $permission = new Permissions();
                        $permission->save([
                            'module' => $module,
                            'controller' => $controller,
                            'action' => $action
                        ]);
                        $permissionrole = new Permissionroles();
                        $permissionrole->permission_id = $permission->id;
                        $permissionrole->role_id = 1;
                        $permissionrole->save();
                    }
                }
            }
        }
        foreach ($inDb as $key => $permission) {
            if (!array_key_exists($key, $inFolders)) {
                foreach ($permission->permissionroles as $permissionrole) {
                    $permissionrole->delete();
                }
                $permission->delete();
            }
        }
    }
    
    /**
     * Find all the actions
     * @return array
     */
    public static function getControllersActions()
    {
        $controllerActions = array();
        $modules = array(
            'frontend',
            'backend'
        );
        foreach ($modules as $module) {
            foreach (glob(__DIR__ . '/../../' . $module . '/controllers/*') as $path) {
                $pop = explode('/', $path);
                $file = array_pop($pop);
                if ($file == 'ControllerBase.php')
                    continue;
                $class = substr($file, 0, -4);
                foreach (get_class_methods($module . '\controllers\\' . $class) as $method) {
                    if (substr($method, -6) == 'Action') {
                        $controllerActions[$module][str_replace('Controller', '', $class)][] = str_replace('Action', '', $method);
                    }
                }
            }
        }
        return $controllerActions;
    }

}
