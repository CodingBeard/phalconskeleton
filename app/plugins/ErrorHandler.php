<?php

/**
 * Error Handler
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */
class ErrorHandler extends \Phalcon\Mvc\User\Component
{

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
                    if (error_get_last()['type'] == 8) {
                        return;
                    }
                    echo '<pre style="position:absolute;bottom:40px;z-index:1000;">';
                    print_r(error_get_last());
                    echo '</pre>';
                    $error = date('Y-m-d H:i:s') . ' ' . print_r(['uri' => $_SERVER['REQUEST_URI'], 'trace' => debug_backtrace(false)[1]], true) . PHP_EOL;
                    error_log($error, 0);
                }
            });
        }
        else {
            register_shutdown_function(function ()
            {
                if (is_array(error_get_last())) {
                    if (error_get_last()['type'] == 8) {
                        return;
                    }
                    if (error_get_last()['type'] == 1) {
                        header('Location: /error');
                    }
                    $error = date('Y-m-d H:i:s') . ' ' . print_r(['uri' => $_SERVER['REQUEST_URI'], 'trace' => debug_backtrace(false)[1]], true) . PHP_EOL;
                    error_log($error, 0);
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
