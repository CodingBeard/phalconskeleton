<?php

/**
 * Beanstalk task
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */
class BeanstalkTask extends \Phalcon\CLI\Task
{

    public function mainAction()
    {
        $pidfile = __DIR__ . '/../pids/beanstalk.pid';
        if (is_file($pidfile)) {
            $pid = file_get_contents($pidfile);
            if (is_dir('/proc/' . $pid) && $pid) {
                die;
            }
        }
        file_put_contents(__DIR__ . '/../pids/beanstalk.pid', getmypid());
        
        /**
         * Remove pid when done
         */
        register_shutdown_function(function ()
        {
            unlink(__DIR__ . '/../pids/beanstalk.pid');
        });

        $serializer = new \SuperClosure\Serializer();

        while (($job = $this->queue->reserve())) {

            $details = $job->getBody();
            
            /**
             * If fatal error or execption, remove job and log error
             */
            register_shutdown_function(function () use ($details, $job)
            {
                if (is_array(error_get_last())) {
                    if (error_get_last()['type'] == 1 || error_get_last()['type'] == 4096) {
                        $details['error'] = error_get_last();
                        $error = date('Y-m-d H:i:s') . ' ' . print_r($details, true) . PHP_EOL;
                        file_put_contents(__DIR__ . '/../../logs/clierror.log', $error, FILE_APPEND);
                        echo $error;
                    }
                }
                $job->delete();
            });
            
            $unserialized = $serializer->unserialize($details['function']);

            if (is_callable($unserialized)) {
                $unserialized($this);
                $job->delete();
                
                /**
                 * If error, log
                 */
                if (is_array(error_get_last())) {
                    if (error_get_last()['type'] != 8) {
                        $details['error'] = error_get_last();
                        $error = date('Y-m-d H:i:s') . ' ' . print_r($details, true) . PHP_EOL;
                        file_put_contents(__DIR__ . '/../../logs/clierror.log', $error, FILE_APPEND);
                        echo $error;
                    }
                }
            }
            
        }
    }

}
