<?php

/**
 * Navigation controller, url: /admin/navigation
 *
 * @category
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace backend\controllers;

use CodingBeard\Forms\Fields\Radio;
use CodingBeard\Forms\Fields\Sortable;
use CodingBeard\Forms\Fields\Textbox;
use models\Navbars;
use models\Navlinks;
use models\Permissions;

class NavbarsController extends ControllerBase
{

    /**
     * Index page
     */
    public function indexAction()
    {
        $this->tag->appendTitle("Navigation manager");
        $this->view->navbars = Navbars::find();
    }

    /**
     * Create a navbar
     */
    public function newAction()
    {
        $this->tag->appendTitle("New Navbar");

        $form = $this->forms;
        $form->title = 'New Navbar';
        $form->submitButton = 'Save';
        $form->cancelHref = 'admin/navbars';

        $form
            ->addField(new Textbox([
                'key' => 'name',
                'label' => 'Name',
                'required' => true,
            ]));

        if ($form->validate()) {
            $role = $form->addToModel(new Navbars());
            if ($role->save()) {
                $this->auth->redirect('admin/navbars', 'success', 'Navbar Created.');
            }
        }
        $form->render();
    }

    /**
     * Edit a navbar
     * @param int $navbar_id
     */
    public function editAction($navbar_id)
    {
        $this->tag->appendTitle("Edit Navbar");
        $navbar = Navbars::findFirstById($navbar_id);
        if (!$navbar) {
            $this->auth->redirect('admin/navbars', 'error', 'Invalid Navbar ID.');
        }

        $form = $this->forms;
        $form->title = 'Edit Navbar';
        $form->submitButton = 'Save';
        $form->cancelHref = 'admin/navbars';

        $form
            ->addField(new Textbox([
                'key' => 'name',
                'label' => 'Name',
                'required' => true,
                'default' => $navbar->name
            ]));

        if ($form->validate()) {
            $role = $form->addToModel($navbar);
            if ($role->save()) {
                $this->auth->redirect('admin/navbars', 'success', 'Navbar Updated.');
            }
        }
        $form->render();
    }

    /**
     * Edit a navbar
     * @param int $navbar_id
     */
    public function manageAction($navbar_id)
    {
        $navbar = Navbars::findFirstById($navbar_id);

        if (!$navbar) {
            $this->auth->redirect('admin/navbars', 'error', 'Invalid navbar ID.');
        }
        $form = $this->forms;
        $form->outerRatio = 0;
        $form->innerRatio = 12;
        $form->title = 'Manage Navbar: ' . $navbar->name;
        $form->description = 'The first Link must be level 0';
        $form->submitButton = 'Save';
        $form->cancelHref = 'admin/navbars';

        $form
            ->addField(new Sortable([
                'key' => 'links',
                'size' => 12,
                'headers' => ['Level', 'Name', 'Link'],
                'options' => function () use ($navbar) {
                    if ($navbar->navlinks->count()) {
                        $options = [];
                        foreach ($navbar->navlinks as $navlink) {
                            $options[] = [
                                new Radio(['key' => 'level', 'inline' => true, 'options' => [
                                    ['value' => '0', 'default' => is_null($navlink->parent_id)],
                                    ['value' => '1', 'default' => !is_null($navlink->parent_id)],
                                    ['value' => '2', 'default' => !is_null($navlink->parent->parent_id)],
                                ]]),
                                new Textbox(['key' => 'label', 'required' => true, 'default' => $navlink->label]),
                                new Textbox(['key' => 'link', 'required' => true, 'default' => $navlink->link]),
                            ];
                        }
                        return $options;
                    }
                    return [[
                        new Radio(['key' => 'level', 'inline' => true, 'options' => [
                            ['value' => '0', 'default' => true],
                            ['value' => '1', 'default' => false],
                            ['value' => '2', 'default' => false],
                        ]]),
                        new Textbox(['key' => 'label', 'required' => true]),
                        new Textbox(['key' => 'link', 'required' => true]),
                    ]];
                }
            ]));

        if ($form->validate()) {
            if ($this->request->getPost('links')[0]['level'] == 0) {
                $navbar->navlinks->delete();
                foreach ($this->request->getPost('links') as $link) {
                    //remove leading slashes, we use url() in the volt files
                    if ($link['link'][0] == '/') {
                        $link['link'] = substr($link['link'], 1);
                    }
                    $navlink = new Navlinks();
                    $navlink->level = $link['level'];
                    $navlink->label = $link['label'];
                    $navlink->link = $link['link'];
                    $navlink->navbar_id = $navbar->id;

                    if ($link['level'] == 1) {
                        //set the parent id if it is a sublink
                        $navlink->parent_id = $last0Navlink->id;
                    }
                    else if ($link['level'] == 2) {
                        $navlink->parent_id = $last1Navlink->id;
                    }

                    /**
                     * Check to see if the link is in the permissions table,
                     * if so attach it to the navlink object. Used for hiding
                     * inaccessible links from people
                     */
                    $router = $this->router;
                    $router->handle($this->config->application->baseUri . $link['link']);
                    if ($router->wasMatched()) {
                        $bind = [];
                        foreach ([$router->getModuleName(), $router->getControllerName(), $router->getActionName()] as $value) {
                            $bind[] = strtolower(str_replace('-', '', $value));
                        }
                        $permission = Permissions::findFirst([
                            'module = ?0 AND controller = ?1 AND action = ?2',
                            'bind' => $bind
                        ]);
                        if ($permission) {
                            $navlink->permission_id = $permission->id;
                        }
                    }

                    $navlink->save();

                    if ($link['level'] == 0) {
                        //set the potential parent navlink for the next navlink
                        $last0Navlink = $navlink;
                    }
                    else if ($link['level'] == 1) {
                        $last1Navlink = $navlink;
                    }
                }
                $this->auth->redirect('admin/navbars', 'success', 'Navbar Updated.');
            }
        }
        $form->render();
    }

}
