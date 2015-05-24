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

namespace CodingBeard;

use Exception;
use Phalcon\Mvc\User\Component;

class ErrorHandler extends Component
{

    /**
     * Custom error handler to allow easier debugging
     * @param bool $showErrors
     */
    public static function setErrorHandler($showErrors = false)
    {
        /* Catch fatal errors */
        register_shutdown_function(function () use ($showErrors) {
            $error = error_get_last();
            if ($error) {
                if ($error['type'] == 1) {
                    $message = "{$error['message']}" . PHP_EOL
                        . "In: {$error['file']}, On line: {$error['line']}" . PHP_EOL
                        . print_r(['uri' => $_SERVER['REQUEST_URI']], true) . PHP_EOL . PHP_EOL;
                    error_log(print_r(['uri' => $_SERVER['REQUEST_URI']], true), 0);
                    if ($showErrors) {
                        echo $message;
                    }
                    else {
                        header('Location: /error');
                    }
                }
            }
        });

        /* Catch all other errors */
        set_error_handler(function ($errno, $errstr, $errfile, $errline) use ($showErrors) {
            if (!(error_reporting() & $errno)) {
                return;
            }

            switch ($errno) {
                case E_USER_NOTICE:
                    break;
                default:
                    $message = "$errstr" . PHP_EOL
                        . "In: $errfile, On line: $errline" . PHP_EOL
                        . print_r(['uri' => $_SERVER['REQUEST_URI']], true) . PHP_EOL . PHP_EOL;
                    error_log(print_r(['uri' => $_SERVER['REQUEST_URI']], true), 0);
                    if ($showErrors) {
                        $_SESSION['errors'][] = $message;
                    }
                    break;
            }
            return true;
        });
    }

    public static function printErrors($showErrors = false)
    {
        if ($showErrors) {
            if (is_array($_SESSION['errors'])) {
                foreach ($_SESSION['errors'] as $error) {
                    echo '<pre>';
                    echo $error;
                    echo '</pre>';
                }
            }
        }
        unset($_SESSION['errors']);
    }

    /**
     * Exception handler
     * @param Exception $e
     * @param bool $showErrors
     */
    public static function handleException($e, $showErrors = false)
    {
        if ($showErrors) {
            echo '<pre>' . $e->getMessage() . PHP_EOL;
            echo $e->getTraceAsString();
        }
        else {
            header('Location: /error');
        }
        error_log($e->getMessage() . PHP_EOL . $e->getTraceAsString() . print_r(['uri' => $_SERVER['REQUEST_URI']], true), 0);
    }

}
