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
        $change = \Emailchanges::findFirst([
            'id = :a:',
            'bind' => ['a' => 1]
        ]);
        $variables = ['user' => $user, 'emailchange' => $change, 'token' => 'd1aXe7wJfjt57lfKufZv'];
        echo $this->emails->render('account', 'changeEmailRevoke', $variables, true);
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
        //$this->emails->emailVerification($user, 'IO3gla4lbmg6MZ5fe02b');
    }

    public function beanstalkAction()
    {
        $this->queue->addJob(function ($that)
        {

        });
    }

    public function indexAction()
    {
        
    }

}
