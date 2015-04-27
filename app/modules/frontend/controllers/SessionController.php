<?php

/**
 * Session controller, url: /session
 *
 * @category
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace frontend\controllers;

use CodingBeard\Forms\Fields\Captcha;
use CodingBeard\Forms\Fields\Password;
use CodingBeard\Forms\Fields\Textbox;
use models\Users;

class SessionController extends ControllerBase
{

    /**
     * Login page
     */
    public function loginAction()
    {
        $this->tag->appendTitle("Login");

        $form = $this->forms;
        $form->title = 'Login';
        $form->cancelButton = 'register';
        $form->cancelHref = 'register';

        $form
            ->addField(new Textbox([
                'key' => 'email',
                'label' => 'Email',
                'required' => true,
            ]))
            ->addField(new Password([
                'key' => 'password',
                'label' => 'Password',
                'sublabel' => '<a href="/account/reset-pass">Forgotten password?</a>',
                'required' => true,
            ]));

        if (($user = $this->auth->checkAuthCookie())) {
            $this->auth->logUserIn($user);
            $this->auth->createAuthCookie();
            return $this->auth->redirect('', 'success', 'Welcome back' . $this->escaper->escapeHtml($user->firstName) . '.');
        }

        if ($this->auth->loginCaptcha()) {
            $form->addField(new Captcha());
        }
        if ($form->validate()) {
            $user = Users::findFirstByEmail($this->request->getPost('email', 'email'));
            if (!$user) {
                $this->auth->attemptThrolling(null);
                return $this->auth->redirect('login', 'error', 'That email is not registered with us.');
            }

            if (!$user->checkPass($this->request->getPost('password', 'trim'))) {
                $this->auth->attemptThrolling($user->id);
                return $this->auth->redirect('login', 'error', 'Incorrect password.');
            }

            if ($user->hasRole('Disabled')) {
                return $this->auth->redirect('login', 'error', 'Your account has been disabled.');
            }

            $this->auth->logUserIn($user);
            $this->auth->createAuthCookie();

            if ($this->request->get('continue')) {
                return $this->auth->redirect($this->request->get('continue'), 'success', 'Welcome back ' . $this->escaper->escapeHtml($user->firstName) . '.');
            }
            else {
                return $this->auth->redirect('', 'success', 'Welcome back ' . $this->escaper->escapeHtml($user->firstName) . '.');
            }
        }

        $form->render();
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
