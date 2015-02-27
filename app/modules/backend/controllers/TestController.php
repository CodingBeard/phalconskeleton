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
            'bind' => ['a' => 3]
        ]);
        //$authtoken = \Authtokens::newToken(['user_id' => $user->id, 'type' => 'emailverification']);
        //$token = $authtoken->string;

        $this->queue->addJob(function ($that) use ($user, $token)
        {
            //$that->emails->emailVerification($user, $token);
        });
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
