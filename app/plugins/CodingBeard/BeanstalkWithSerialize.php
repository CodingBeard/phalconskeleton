<?php

/**
 * Beanstalk
 *
 * This extends the normal beanstalk queue to allow for anonymous functions to be queued and access the DI
 *
 * @category
 * @package BeardSite
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace CodingBeard;

use Closure;
use Phalcon\Mvc\User\Component;
use Phalcon\Queue\Beanstalk;
use SuperClosure\Serializer;

class BeanstalkWithSerialize extends Component
{

    /**
     * @var Beanstalk
     */
    private $beanstalk;

    /**
     * @param $properties array
     */
    public function __construct($properties)
    {
        $this->beanstalk = new Beanstalk($properties);
    }

    public function addJob(Closure $job, $options = null)
    {
        $serialize = new Serializer();
        $serialized = $serialize->serialize($job);
        $this->beanstalk->put([
            'function' => $serialized,
            'uri'      => $_SERVER['REQUEST_URI'],
            'key'      => $this->config->beanstalk->key,
        ], $options);
    }

    public function __call($name, $arguments)
    {
        return $this->beanstalk->$name();
    }

}
