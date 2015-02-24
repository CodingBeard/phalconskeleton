<?php

/**
 * Account controller, url: /account
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */

namespace frontend\controllers;

class AccountController extends ControllerBase
{

    /**
     * Registration page
     */
    public function registerAction()
    {
        $this->tag->appendTitle("Register");

        $form = $this->form;
        $form->title = 'Register';
        $form->description = 'Create an account';
        $form->submitButton = 'Create';

        $form
        ->addField(new \Forms\Fields\Textbox(['key' => 'firstName', 'label' => 'First Name', 'required' => true, 'size' => 6]))
        ->addField(new \Forms\Fields\Textbox(['key' => 'lastName', 'label' => 'Last Name', 'required' => true, 'size' => 6]))
        ->addField(new \Forms\Fields\Textbox([
            'key' => 'email',
            'label' => 'Email',
            'required' => true,
            'unique' => ['model' => '\Users', 'field' => 'email'],
            'repeat' => true,
            'size' => 6
        ]))
        ->addField(new \Forms\Fields\Password([
            'key' => 'password',
            'label' => 'Password',
            'sublabel' => 'Minimum of 8 characters',
            'required' => true,
            'repeat' => true,
            'pattern' => '^.{8,1000}$',
            'size' => 6
        ]))
        ->addField(new \Forms\Fields\Dateselect(['key' => 'DoB', 'label' => 'Date of birth', 'required' => true]))
        ->addField(new \Forms\Fields\Checkbox([
            'key' => 'tos',
            'label' => 'I have read and agree to the <a href="/site/terms">Terms</a>',
            'required' => true
        ]))
        ->addField(new \Forms\Fields\Captcha());

        if ($this->request->isPost()) {
            if ($this->auth->checkToken($_POST)) {
                if ($form->validate()) {
                    $user = $form->addToModel(new \Users());
                    $user->hashPass();
                    $user->active = 1;
                    
                    if ($user->save()) {
                        $user->addRole('Member');
                        $user->addRole('Unverified Email');
                        
                        $token = \Authtokens::newToken(['user_id' => $user->id, 'type' => 'emailverification']);
                        $this->emails->emailVerification($user, $token);
                        
                        $this->flashSession->success('Account created successfully, please login.');
                        $this->response->redirect('account/login');
                        $this->view->disable();
                    }
                    
                }
            }
        }
        $this->view->form = $form->getForm();
        $this->view->formjs = $form->getJS();
    }
    
    public function verifyemailAction($token = false)
    {
        if ($token) {
            
        }
    }

}
