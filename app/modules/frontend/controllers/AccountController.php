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
            if ($this->auth->checkToken()) {
                if ($form->validate()) {
                    $user = $form->addToModel(new \Users());
                    $user->hashPass();
                    $user->active = 1;

                    if ($user->save()) {
                        $user->addRole('Member');
                        $user->addRole('Unverified Email');

                        $authtoken = \Authtokens::newToken(['user_id' => $user->id, 'type' => 'emailverification']);
                        $authtoken->save();
                        $this->emails->emailVerification($user, $authtoken->token);

                        return $this->auth->redirect('account/login', 'success', 'Account created successfully, please login.');
                    }
                }
            }
        }
        $this->view->form = $form->getForm();
        $this->view->formjs = $form->getJS();
    }

    /**
     * Verify an email
     * @param string $token
     * @return bool
     */
    public function verifyemailAction($token = false)
    {
        if (!$token) {
            return $this->auth->redirect('', 'error', 'Token is missing.');
        }

        $authtoken = \Authtokens::findFirst([
            'type = "emailverification" AND token = :a: AND expires > :b:',
            'bind' => ['a' => $token, 'b' => date('Y-m-d H:i:s')]
        ]);
        if (!$authtoken) {
            sleep(1);
            return $this->auth->redirect('', 'error', 'That token is not valid.');
        }

        $user = \Users::findFirst([
            'id = :a:',
            'bind' => ['a' => $authtoken->user_id]
        ]);
        if ($user) {
            $user->removeRole('Unverified Email');
            $user->addRole('Verified Email');

            $authtoken->expires = date('Y-m-d H:i:s');
            $authtoken->save();

            return $this->auth->redirect('', 'success', 'Thanks for verifying your email.');
        }
    }

}
