<?php

/**
 * Permissions controller, url: /admin/permissions/
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */

namespace backend\controllers;

class PermissionsController extends ControllerBase
{

    /**
     * List pages with permissions, allow roles to be added/removed
     */
    public function indexAction()
    {
        $this->tag->appendTitle("Permissions");
        $roles = \Roles::find(array('order' => 'level'));
        
        foreach ($roles as $role) {
            if ($role->id == 1)
                continue;
            $tagValues[$role->name] = $role->id;
            $tagLabels[] = $role->name;
        }
        
        $this->view->tagValues = $tagValues;
        $this->view->tagLabels = $tagLabels;
        
        $this->view->permissions = \Permissions::find(['order' => 'module, controller, action']);
    }

    /**
     * Set the roles for a permission through ajax
     * @param int $id
     */
    public function setAction($id)
    {
        if (!$this->request->isAjax() || !$this->request->isPost()) {
            echo json_encode(['status' => 'NOK', 'message' => 'Not ajax/post']);
            return;
        }

        $permission = \Permissions::findFirstById($id);
        if (!$permission) {
            echo json_encode(['status' => 'NOK', 'message' => 'Permission does not exist']);
            return;
        }

        $roleNames = $this->request->getPost('roles');
        if ($permission->setRoles($roleNames)) {
            echo json_encode(['status' => 'OK', 'message' => 'Permissions updated.']);
            return;
        }

        echo json_encode(['status' => 'NOK', 'message' => 'Could not set roles.']);
    }

    /**
     * Updates the permissions in database
     */
    public function updatepermissionsAction()
    {
        $modules = $this->getControllersActions();

        $permissionsInDb = [];
        foreach (\Permissions::find() as $permission) {
            $permissionsInDb[($permission->module . $permission->controller . $permission->action)] = $permission;
        }

        $inFolders = [];
        foreach ($modules as $module => $controllers) {
            foreach ($controllers as $controller => $actions) {
                foreach ($actions as $action) {

                    $inFolders[($module . $controller . $action)] = ($module . $controller . $action);

                    if (!array_key_exists(($module . $controller . $action), $permissionsInDb)) {
                        $permission = new \Permissions();
                        $permission->module = $module;
                        $permission->controller = $controller;
                        $permission->action = $action;
                        $permission->save();
                    }
                }
            }
        }

        foreach ($permissionsInDb as $key => $permission) {
            if (!array_key_exists($key, $inFolders)) {
                $permission->delete();
            }
        }
        $this->auth->redirect('admin/permissions', 'success', 'Permissions updated.');
    }

    /**
     * Find all the controllers/actions
     * @return array
     */
    public function getControllersActions()
    {
        $controllersActions = [];
        foreach ($this->config->modules->controllers as $module => $controllerDir) {
            foreach (scandir($controllerDir) as $file) {
                if (in_array($file, ['ControllerBase.php', '..', '.'])) {
                    continue;
                }

                $controllerName = str_replace(['Controller', '.php'], '', $file);

                foreach (get_class_methods("{$module}\controllers\\{$controllerName}Controller") as $method) {
                    if (substr($method, -6) == 'Action') {
                        $controllersActions[$module][strtolower($controllerName)][] = str_replace('Action', '', $method);
                    }
                }
            }
        }
        $standalonePages = \Pages::findByStandalone(1);
        if ($standalonePages) {
            $router = clone $this->router;
            foreach ($standalonePages as $page) {
                $router->handle('/' . $page->url);
                if ($router->wasMatched()) {
                    $controllersActions[$router->getModuleName()][$router->getControllerName()][] = $router->getActionName();
                }
            }
        }
        return $controllersActions;
    }

}
