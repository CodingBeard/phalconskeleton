<?php

/**
 * Security/ACL plugin
 * 
 * Requires models: User, Userroles, Roles, Permissionroles, Permissions
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
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
        if (!isset($this->session->get('acl')[$this->module])) {

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
            
            if ($this->session->has('acl')) {
                $modules = $this->session->get('acl');
            }
            
            $modules[$this->module] = $acl;
            $this->session->set('acl', $modules);
        }
        return $this->session->get('acl')[$this->module];
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

        /**
         * If we're accessing the pagecontents system, change the controller/action
         * to the URI params so we can query the db for the page's permissions
         * instead of the pagecontents/view default permissions.
         * Also filter for case-insensitivity/hyphens
         */
        if ($controller == 'pagecontents' && $action == 'view') {
            $controller = str_replace('-', '', strtolower($this->router->getControllerName()));
            $action = str_replace('-', '', strtolower($this->router->getActionName()));
        }

        /**
         * If not logged in, check for cookie tokens to auto login
         */
        if (!$auth->loggedIn) {
            if (($user = $auth->checkAuthCookie())) {
                $auth->logUserIn($user);
                $this->auth->createAuthCookie();
            }
        }

        $acl = $this->getAcl();

        if ($acl->isAllowed('Guest', $controller, $action) == \Phalcon\Acl::ALLOW) {
            return true;
        }

        if ($auth->loggedIn) {
            $auth->setRoles();
            if ($auth->getUser()->hasRole('Deactivated')) {
                $auth->redirect('', 'error', 'Your account has been deactivated.');
                return false;
            }
            foreach ($auth->roles as $userrole) {
                if ($acl->isAllowed($userrole, $controller, $action) == \Phalcon\Acl::ALLOW) {
                    return true;
                }
            }
            if ($auth->isAdmin) {
                return true;
            }
        }
        $redirect = '?continue=' . substr($_SERVER['REQUEST_URI'], 1);
        return $auth->redirect('login/' . $redirect, 'error', 'You do not have the rights to access that page.');
    }

}
