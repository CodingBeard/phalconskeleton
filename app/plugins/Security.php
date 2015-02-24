<?php

/**
 * Security/ACL plugin
 * 
 * Requires models: User, Userroles, Roles, Permissionroles, Permissions
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */
use Phalcon\Events\Event,
    Phalcon\Mvc\User\Plugin,
    Phalcon\Mvc\Dispatcher;

class Security extends Plugin
{
    /**
     * Name of module initialized in
     * @var string 
     */
    public $module;

    /**
     * 
     * @param \Phalcon\DI $dependencyInjector
     * @param string $module
     */
    public function __construct($dependencyInjector, $module)
    {
        $this->_dependencyInjector = $dependencyInjector;
        $this->module = $module;
    }

    /**
     * Create Acl, add roles and resources
     * @return \Phalcon\Acl\Adapter\Memory
     */
    public function getAcl()
    {
        if (!isset($this->persistent->acl)) {

            $acl = new \Phalcon\Acl\Adapter\Memory();

            $acl->setDefaultAction(\Phalcon\Acl::DENY);

            $roles = \Roles::find();
            foreach ($roles as $role) {
                $acl->addRole($role->name);
            }

            $permissions = \Permissions::find([
                'module = :module:',
                'bind' => ['module' => $this->module]
            ]);

            foreach ($permissions as $permission) {
                $acl->addResource(new \Phalcon\Acl\Resource(strtolower($permission->controller)), strtolower($permission->action));
                foreach ($permission->roles as $role) {
                    $acl->allow($role->name, strtolower($permission->controller), strtolower($permission->action));
                }
            }
            $this->session->acl[$this->module] = $acl;
        }
        return $this->session->acl[$this->module];
    }

    /**
     * Listener function which is attached to Dispatcher
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return boolean
     */
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        //Uncommenting this allows anyone to access anything.
        //return true;

        $auth = $this->auth;

        $controller = strtolower($dispatcher->getControllerName());
        $action = strtolower($dispatcher->getActionName());


        if ($auth->isAdmin)
            return true;

        $acl = $this->getAcl();

        if ($acl->isAllowed('Guest', $controller, $action) == \Phalcon\Acl::ALLOW) {
            return true;
        }

        if ($auth) {
            $auth->setRoles();
            if ($auth->user()->active == 0) {
                $auth->redirect('', 'error', 'Your account has been deactivated.');
                return false;
            }
            foreach ($auth->roles as $userrole) {
                if ($acl->isAllowed($userrole, $controller, $action) == \Phalcon\Acl::ALLOW) {
                    return true;
                }
            }
        }
        $redirect = '?continue=';
        if ($this->module == 'backend')
            $redirect .= 'admin/';
        $redirect .= $controller . '/' . $action . '/';
        foreach ($dispatcher->getParams() as $param) {
            $redirect .= $param . '/';
        }
        return $auth->redirect('account/login/' . $redirect, 'error', 'You do not have the rights to access that page.');
    }

}
