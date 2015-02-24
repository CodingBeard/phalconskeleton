<?php

/**
 * Beanstalk
 * 
 * This extends the normal beanstalk queue to allow for anonymous functions to be queued
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */
class BeanstalkWithSerialize extends \Phalcon\Queue\Beanstalk
{
    
    public function addJob(\Closure $job, $options = null)
    {
        $serialize = new \SuperClosure\Serializer();
        $serialized = $serialize->serialize($job);
        return $this->put($serialized, $options);
    }

}
