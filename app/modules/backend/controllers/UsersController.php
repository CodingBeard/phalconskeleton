<?php

/**
 * Users controller, url: /admin/users/
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace backend\controllers;

use CodingBeard\Forms\Fields\Dateselect;
use CodingBeard\Forms\Fields\Tagbox;
use CodingBeard\Forms\Fields\Textarea;
use CodingBeard\Forms\Fields\Textbox;
use models\Roles;
use models\Users;

class UsersController extends ControllerBase
{

    /**
     * Display all users
     */
    public function indexAction()
    {
        $this->tag->appendTitle("Users");
        $this->view->users = Users::find();
    }

    /**
     * Edit a user's information and userroles
     * @param int $user_id
     */
    public function editAction($user_id)
    {
        $this->tag->appendTitle("Edit User");
        $user = Users::findFirstById($user_id);
        if (!$user) {
            $this->auth->redirect('admin/users', 'error', 'Invalid User ID.');
        }

        $form = $this->form;
        $form->title = 'Edit User: ' . $user->getName();
        $form->submitButton = 'Save';
        $form->cancelHref = 'admin/users';

        $form
        ->addField(new Textbox([
            'key' => 'firstName',
            'label' => 'First Name',
            'required' => true,
            'default' => $user->firstName,
            'size' => 6
        ]))
        ->addField(new Textbox([
            'key' => 'lastName',
            'label' => 'Last Name',
            'required' => true,
            'default' => $user->lastName,
            'size' => 6
        ]))
        ->addField(new Textbox([
            'key' => 'email',
            'label' => 'Email',
            'required' => true,
            'default' => $user->email,
            'size' => 6
        ]))
        ->addField(new Dateselect([
            'key' => 'DoB',
            'label' => 'Date of birth',
            'required' => true,
            'default' => $user->DoB,
            'size' => 6
        ]))
        ->addField(new Tagbox([
            'key' => 'userroles',
            'label' => 'User Roles',
            'required' => true,
            'size' => 12,
            'autocompleteOnFocus' => true,
            'options' => function () use ($user)
            {
                $roles = [];
                foreach (Roles::find() as $role) {
                    $roles[$role->id] = ['value' => $role->id, 'label' => $role->name, 'default' => false];
                }
                foreach ($user->roles as $role) {
                    $roles[$role->id]['default'] = true;
                }
                return $roles;
            }
        ]));

        if ($form->validate()) {
            $user = $form->addToModel($user);
            $user->setRoles($this->request->getPost('userroles'));

            if ($user->save()) {
                $this->auth->redirect('admin/users', 'success', 'User updated.');
            }
        }

        $form->render();
    }

    /**
     * List User Roles
     */
    public function rolesAction()
    {
        $this->tag->appendTitle("User Roles");
        $this->view->roles = Roles::find(['order' => 'level']);
    }

    /**
     * Create a User Role
     * @param int $role_id
     */
    public function newroleAction()
    {
        $this->tag->appendTitle("New Role");

        $form = $this->form;
        $form->title = 'New Role';
        $form->submitButton = 'Save';
        $form->cancelHref = 'admin/users/roles';

        $form
        ->addField(new Textbox([
            'key' => 'name',
            'label' => 'Name',
            'required' => true,
            'size' => 6
        ]))
        ->addField(new Textbox([
            'key' => 'level',
            'label' => 'Level',
            'default' => 100,
            'size' => 6
        ]))
        ->addField(new Textarea([
            'key' => 'description',
            'label' => 'Description',
            'size' => 12
        ]));

        if ($form->validate()) {
            $role = $form->addToModel(new Roles());
            if ($role->save()) {
                $this->auth->redirect('admin/users/roles', 'success', 'Role Created.');
            }
        }
        $form->render();
    }

    /**
     * Edit a User Role
     * @param int $role_id
     */
    public function editroleAction($role_id)
    {
        $this->tag->appendTitle("Edit Role");
        $role = Roles::findFirstById($role_id);
        if (!$role) {
            $this->auth->redirect('admin/users/roles', 'error', 'Invalid User ID.');
        }

        $form = $this->form;
        $form->title = 'Edit Role: ' . $role->name;
        $form->submitButton = 'Save';
        $form->cancelHref = 'admin/users/roles';

        $form
        ->addField(new Textbox([
            'key' => 'name',
            'label' => 'Name',
            'required' => true,
            'default' => $role->name,
            'size' => 6
        ]))
        ->addField(new Textbox([
            'key' => 'level',
            'label' => 'Level',
            'default' => $role->level,
            'size' => 6
        ]))
        ->addField(new Textarea([
            'key' => 'description',
            'label' => 'Description',
            'default' => $role->description,
            'size' => 12
        ]));

        if ($form->validate()) {
            $role = $form->addToModel($role);
            if ($role->save()) {
                $this->auth->redirect('admin/users/roles', 'success', 'Role updated.');
            }
        }
        $form->render();
    }

}
