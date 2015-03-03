<?php

/**
 * Beanstalk
 *
 * This extends the normal beanstalk queue to allow for anonymous functions to be queued
 *
 * @category
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace CodingBeard;

use Closure;
use Phalcon\Queue\Beanstalk;
use SuperClosure\Serializer;

class BeanstalkWithSerialize extends Beanstalk
{

    public function addJob(Closure $job, $options = null)
    {
        $serialize = new Serializer();
        $serialized = $serialize->serialize($job);
        $trace = ['uri' => $_SERVER['REQUEST_URI'], 'trace' => debug_backtrace(false)[1]];
        return $this->put(['function' => $serialized, 'trace' => $trace], $options);
    }

}
