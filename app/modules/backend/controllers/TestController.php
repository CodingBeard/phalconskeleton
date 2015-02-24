<?php

/**
 * Test controller, url: /admin/test
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */

namespace backend\controllers;

class TestController extends ControllerBase
{

    /**
     * Email design page
     */
    public function emaildesignAction()
    {
        $user = \Users::findFirst([
            'id = :a:',
            'bind' => ['a' => 1]
        ]);
        $variables = ['user' => $user, 'token' => 'IO3gla4lbmg6MZ5fe02b'];
        echo $this->emails->render('account', 'emailVerification', $variables, true);
    }

    /**
     * Send an email
     */
    public function emailsendAction()
    {
        $user = \Users::findFirst([
            'id = :a:',
            'bind' => ['a' => 1]
        ]);
        $this->emails->emailVerification($user, 'IO3gla4lbmg6MZ5fe02b');
    }

    public function beanstalkAction()
    {
        $this->queue->addJob(function ($di)
        {
            echo $di->get('config')->application->name, PHP_EOL;
        });
    }

}
