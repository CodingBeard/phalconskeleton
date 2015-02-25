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
            'unique' => ['model' => '\Users', 'field' => 'email', 'message' => 'That email is already registered'],
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
                        $this->queue->addJob(function () use ($user, $authtoken)
                        {
                            $this->emails->emailVerification($user, $authtoken->token);
                        });

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

    public function resetpassAction($token = false)
    {
        $this->tag->appendTitle("Reset Password");

        if ($token) {
            if (($user_id = \Authtokens::checkToken('passreset', $token))) {
                $this->auth->logUserIn(\Users::findFirst([
                    'id = :a:',
                    'bind' => ['a' => $user_id]
                ]));
                $this->session->set('reset-pass', true);
                return $this->auth->redirect('account/change-pass', 'success', 'Please set a new password.');
            }
        }
        $form = $this->form;
        $form->title = 'Reset Password';
        $form->description = 'We will send you an email so you can reset your password';
        $form->cancelHref = '/';

        $form
        ->addField(new \Forms\Fields\Textbox([
            'key' => 'email',
            'label' => 'Email',
            'sublabel' => 'The address you registered with',
            'required' => true,
            'exists' => ['model' => '\Users', 'field' => 'email', 'message' => 'That account does not exists'],
        ]))
        ->addField(new \Forms\Fields\Captcha());

        if ($this->request->isPost()) {
            if ($this->auth->checkToken()) {
                if ($form->validate()) {
                    $user = \Users::findFirst([
                        'email = :a:',
                        'bind' => ['a' => $this->request->getPost('email', 'trim')]
                    ]);
                    $token = \Authtokens::newToken(['user_id' => $user->id, 'type' => 'passreset', 'unique' => true, 'expires' => 1]);

                    $this->queue->addJob(function ($that) use ($user, $token)
                    {
                        $that->emails->resetPass($user, $token);
                    });
                    $this->auth->redirect('account/reset-pass', 'success', 'Password reset email sent, please check your spam folder if you cannot find it.');
                }
            }
        }
        $this->view->form = $form->getForm();
        $this->view->formjs = $form->getJS();
    }

    public function changepassAction()
    {
        $this->tag->appendTitle("Change Password");

        $form = $this->form;
        $form->title = 'Change Password';
        $form->cancelHref = '/';

        if (!$this->session->has('reset-pass')) {
            $form
            ->addField(new \Forms\Fields\Password([
                'key' => 'oldpassword',
                'label' => 'Old Password',
                'required' => true,
            ]));
        }

        $form
        ->addField(new \Forms\Fields\Password([
            'key' => 'password',
            'label' => 'New Password',
            'sublabel' => 'Minimum of 8 characters',
            'required' => true,
            'repeat' => true,
            'pattern' => '^.{8,1000}$',
            'size' => 6
        ]));

        if ($this->request->isPost()) {
            if ($this->auth->checkToken()) {
                if ($form->validate()) {
                    $user = $this->auth->getUser();
                    
                    if ($this->session->has('reset-pass')) {
                        $this->session->remove('reset-pass');
                    }
                    else {
                        if (!password_verify($this->request->getPost('oldpassword', 'trim'), $user->password)) {
                            return $this->auth->redirect('account/change-pass', 'error', 'Incorrect password.');
                        }
                    }
                    
                    $user->password = $this->request->getPost('password', 'trim');
                    $user->hashPass();
                    $user->save();
                    
                    $this->auth->redirect('', 'success', 'Password changed');
                }
            }
        }
        $this->view->form = $form->getForm();
        $this->view->formjs = $form->getJS();
    }

}
