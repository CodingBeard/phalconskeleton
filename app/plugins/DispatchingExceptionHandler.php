<?php

/**
 * Error Pages
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */
class DispatchingExceptionHandler extends \Phalcon\Mvc\User\Component
{

    /**
     * Serve pagecontents system or 
     * Redirect to a 404 or 500 page if we catch an exception
     * @param \Phalcon\Events\Event $event
     * @param \Phalcon\Mvc\Dispatcher $dispatcher
     * @param \Phalcon\Mvc\Dispatcher\Exception $exception
     * @return boolean
     */
    public function beforeException($event, $dispatcher, $exception)
    {
        switch ($exception->getCode()) {
            case \Phalcon\Mvc\Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
            case \Phalcon\Mvc\Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                
                //filter for case-insensitivity and hyphens
                $url = str_replace('-', '', strtolower(substr($_SERVER['REQUEST_URI'], 1)));
                
                $page = \Pages::findFirst([
                    'url = :a: AND standalone = 1',
                    'bind' => ['a' => $url]
                ]);
                if ($page) {
                    $dispatcher->forward([
                        'controller' => 'pagecontents',
                        'action' => 'view',
                        'params' => [$page->id]
                    ]);
                }
                else {
                    $dispatcher->forward([
                        'controller' => 'error',
                        'action' => 'notFound',
                    ]);
                }
                return false;
            default:
                if (!$this->config->application->showErrors) {
                    $dispatcher->forward([
                        'controller' => 'error',
                        'action' => 'index',
                    ]);
                    return false;
                }
        }
    }


}
