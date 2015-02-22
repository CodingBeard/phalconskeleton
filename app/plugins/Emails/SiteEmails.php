<?php

/**
 * Site Emails
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */

class SiteEmails extends Phalcon\Mvc\User\Plugin
{

    /**
     * Domain name for server, used when sending
     * @var string
     */
    public $domain;
    
    /**
     * Preset footers for sending emails
     * @var array
     */
    public $footers;

    /**
     * Email container class
     * @param \Phalcon\DI $dependencyInjector
     */
    public function __construct($dependencyInjector)
    {
        $this->_dependencyInjector = $dependencyInjector;
        $this->domain = $this->config->mail->domain;
        $this->footers = $this->config->mail->footers;
    }

    public function render($folder, $file, $variables, $testView = false)
    {
        $view = clone $this->view;
        $view->setViewsDir(__DIR__ . '/templates/');
        foreach ($variables as $key => $value) {
            $view->setVar($key, $value);
        }
        $view->start();
        $view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
        $view->render($folder, $file);
        $view->finish();
        $result = $view->getContent();
        
        if (stripos($result, '<pre>')) {
            $split = explode('<pre>', $result);
            $return = (object) ['html' => $split[0], 'text' => $split[1]];
        }
        else {
            $return = (object) ['html' => $result, 'text' => strip_tags($result)];
        }

        if ($testView) {
            return implode('<pre>', $return);
        }
        return $return;
    }

}
