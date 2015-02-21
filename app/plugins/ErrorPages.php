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
use Phalcon\Mvc\Dispatcher,
    Phalcon\Events\Event,
    Phalcon\Mvc\User\Plugin,
    Phalcon\Mvc\Dispatcher\Exception as DispatchException;

class ErrorPages extends Plugin
{

    /**
     * 
     * @param \Phalcon\DI $di
     */
    public function __construct($di)
    {
        $this->_dependencyInjector = $di;
    }

    /**
     * Redirect to a 404 or 500 page if we catch an exception
     * @param \Phalcon\Events\Event $event
     * @param \Phalcon\Mvc\Dispatcher $dispatcher
     * @param \Phalcon\Mvc\Dispatcher\Exception $exception
     * @return boolean
     */
    public function beforeException($event, $dispatcher, $exception)
    {
        switch ($exception->getCode()) {
            case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
            case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                $dispatcher->forward([
                    'controller' => 'error',
                    'action' => 'notFound',
                ]);
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
    
    /**
     * Register shutdown functions that deal with errors
     * @param bool $showErrors
     */
    public static function registerShutdown($showErrors = false)
    {
        if ($showErrors) {
            register_shutdown_function(function ()
            {
                if (is_array(error_get_last())) {
                    if (error_get_last()['type'] == 8)
                        return;
                    echo '<pre style="position:absolute;bottom:40px;z-index:1000;">';
                    print_r(error_get_last());
                    echo '</pre>';
                }
            });
        }
        else {
            register_shutdown_function(function ()
            {
                if (is_array(error_get_last())) {
                    if (error_get_last()['type'] == 1) {
                        header('Location: /error');
                    }
                }
            });
        }
    }

    /**
     * Exception handler
     * @param \Exception $e
     * @param bool $showErrors
     */
    public static function handleException($e, $showErrors = false)
    {
        if ($showErrors) {
            echo '<pre>' . $e->getMessage() . PHP_EOL;
            echo $e->getTraceAsString();
        }
        error_log($e->getMessage() . PHP_EOL . $e->getTraceAsString(), 0);
    }

}
