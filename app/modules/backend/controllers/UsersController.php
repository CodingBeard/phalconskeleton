<?php

/**
 * Index controller, url: /admin/
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */

namespace backend\controllers;

class UsersController extends ControllerBase
{

    /**
     * Index page
     */
    public function indexAction()
    {
        $this->tag->appendTitle("Users");

        $this->view->users = \Users::find();
    }

    public function editAction($user_id)
    {
        $user = \Users::findFirstById($user_id);
        if (!$user) {
            $this->auth->redirect('admin/users', 'error', 'Invalid User ID.');
        }

        $form = $this->form;
        $form->title = 'Edit User: ' . $user->getName();
        $form->submitButton = 'Save';
        $form->cancelHref = 'admin/users';

        $form
        ->addField(new \Forms\Fields\Textbox([
            'key' => 'firstName',
            'label' => 'First Name',
            'required' => true,
            'default' => $user->firstName,
            'size' => 6
        ]))
        ->addField(new \Forms\Fields\Textbox([
            'key' => 'lastName',
            'label' => 'Last Name',
            'required' => true,
            'default' => $user->lastName,
            'size' => 6
        ]))
        ->addField(new \Forms\Fields\Textbox([
            'key' => 'email',
            'label' => 'Email',
            'required' => true,
            'default' => $user->email,
            'size' => 6
        ]))
        ->addField(new \Forms\Fields\Dateselect([
            'key' => 'DoB',
            'label' => 'Date of birth',
            'required' => true,
            'default' => $user->DoB,
            'size' => 6
        ]))
        ->addField(new \Forms\Fields\Tagbox([
            'key' => 'userroles',
            'label' => 'User Roles',
            'required' => true,
            'size' => 12,
            'autocompleteOnFocus' => true,
            'options' => function () use ($user)
            {
                $roles = [];
                foreach (\Roles::find() as $role) {
                    $roles[$role->id] = ['value' => $role->id, 'label' => $role->name, 'default' => false];
                }
                foreach ($user->roles as $role) {
                    $roles[$role->id]['default'] = true;
                }
                return $roles;
            }
        ]));

        if ($this->request->isPost()) {
            if ($this->auth->checkToken()) {
                if ($form->validate()) {
                    $user = $form->addToModel($user, [
                        'firstName', 'lastName', 'email', 'DoB',
                    ]);
                    $user->setRoles($this->request->getPost('userroles'));
                    
                    if ($user->save()) {
                        $this->auth->redirect('admin/users', 'success', 'User updated.');
                    }
                }
            }
        }
        $this->view->form = $form->getForm();
        $this->view->formjs = $form->getJS();
    }

}
