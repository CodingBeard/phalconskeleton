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

namespace Emails;

class SiteEmails extends \Phalcon\Mvc\User\Plugin
{

    /**
     * Domain name for server, used when sending
     * @var string
     */
    public $domain;

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

    /**
     * Render an email template
     * @param string $folder
     * @param string $file
     * @param array $variables
     * @param bool $testView
     * @return object|string
     */
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
        $content = $view->getContent();
        
        $inliner = new \TijsVerkoyen\CssToInlineStyles\CssToInlineStyles();
        $inliner->setCSS(file_get_contents(__DIR__ . '/templates/layouts/style.css'));
        $inliner->setCleanup(true);
        
        if (stripos($content, '<pre>')) {
            $split = explode('<pre>', $content);
            
            $inliner->setHTML($split[0]);
            $html = $inliner->convert();
            
            $result = (object) ['html' => $html, 'text' => $split[1]];
        }
        else {
            $inliner->setHTML($content);
            $html = $inliner->convert();
            
            $newlines = preg_replace('/\s+(\r\n|\r|\n)+/', "\n\n", strip_tags($content, '<a>'));
            $links = preg_replace('~<a.*?href="(https?://[^"]+)".*?>.*?</a>~', '$1', $newlines);
            $text = preg_replace('/[ |\t]+/', " ", $links);
            
            $result = (object) ['html' => $html, 'text' => $text];
        }

        if ($testView) {
            return $result->html . '<pre>' . $result->text . '</pre>';
        }
        return $result;
    }

    /**
     * Send an email verification email
     * @param \Users $user
     * @param string $token
     */
    public function emailVerification($user, $token)
    {
        $content = $this->render('account', 'emailVerification', ['user' => $user, 'token' => $token]);

        $this->mandrill->messages_send([
            'from_email' => 'No-reply@' . $this->domain,
            'from_name' => $this->config->application->name,
            'to' => [['email' => $user->email, 'name' => $user->getName()]],
            'subject' => "Thanks for registering at {$this->config->application->name}! Please verify your email",
            'html' => $content->html,
            'text' => $content->text,
        ], true);
    }

}
