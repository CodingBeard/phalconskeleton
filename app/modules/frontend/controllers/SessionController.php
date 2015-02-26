<?php

/**
 * Session controller, url: /session
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */

namespace frontend\controllers;

class SessionController extends ControllerBase
{

    /**
     * Login page
     */
    public function loginAction()
    {
        $this->tag->appendTitle("Login");

        $form = $this->form;
        $form->title = 'Login';
        $form->cancelButton = 'register';
        $form->cancelHref = 'register';

        $form
        ->addField(new \Forms\Fields\Textbox([
            'key' => 'email',
            'label' => 'Email',
            'required' => true,
        ]))
        ->addField(new \Forms\Fields\Password([
            'key' => 'password',
            'label' => 'Password',
            'sublabel' => '<a href="/account/reset-pass">Forgotten password?</a>',
            'required' => true,
        ]))
        ->addField(new \Forms\Fields\Checkbox([
            'key' => 'rememberme',
            'label' => 'Remember me'
        ]));

        if (($user = $this->auth->checkAuthCookie())) {
            $this->auth->logUserIn($user);
            $this->auth->createAuthCookie();
            return $this->auth->redirect('', 'success', 'Welcome back' . $this->escaper->escapeHtml($user->firstName) . '.');
        }

        if ($this->auth->loginCaptcha()) {
            $form->addField(new \Forms\Fields\Captcha());
        }
        if ($this->request->isPost()) {
            if ($this->auth->checkToken()) {
                if ($form->validate()) {
                    $user = \Users::findFirst([
                        'email = :a:',
                        'bind' => ['a' => $this->request->getPost('email', 'email')]
                    ]);
                    if (!$user) {
                        $this->auth->attemptThrolling(null);
                        return $this->auth->redirect('account/login', 'error', 'That email is not registered with us.');
                    }

                    if (!$user->checkPass($this->request->getPost('password', 'trim'))) {
                        $this->auth->attemptThrolling($user->id);
                        return $this->auth->redirect('account/login', 'error', 'Incorrect password.');
                    }

                    if (!$user->active) {
                        return $this->auth->redirect('account/login', 'error', 'Your account has been disabled.');
                    }

                    $this->auth->logUserIn($user);
                    if ($this->request->getPost('rememberme', 'trim') == 'on') {
                        $this->auth->createAuthCookie();
                    }
                    
                    if ($this->request->get('continue')) {
                        return $this->auth->redirect($this->request->get('continue'), 'success', 'Welcome back ' . $this->escaper->escapeHtml($user->firstName) . '.');
                    }
                    else {
                        return $this->auth->redirect('', 'success', 'Welcome back ' . $this->escaper->escapeHtml($user->firstName) . '.');
                    }
                }
            }
        }

        $this->view->form = $form->getForm();
        $this->view->formjs = $form->getJS();
    }

    /**
     * Log the user out
     * @return bool
     */
    public function logoutAction()
    {
        $this->auth->logUserOut();
        $this->auth->removeAuthCookie();
        return $this->auth->redirect('', 'success', 'Logged out.');
    }

}
