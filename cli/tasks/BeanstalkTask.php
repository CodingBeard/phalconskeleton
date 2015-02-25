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

        $serializer = new \SuperClosure\Serializer();

        while (($job = $this->queue->reserve())) {

            $serialized = $job->getBody();
            $unserialized = $serializer->unserialize($serialized);
            if (is_callable($unserialized)) {
                $unserialized($this->getDI());
            }
            $job->delete();
        }
    }

}
