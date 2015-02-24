<?php

/**
 * Beanstalk
 * 
 * Queue worker
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

$pidfile = __DIR__ . '/beanstalk.pid';
if (is_file($pidfile)) {
    $pid = file_get_contents($pidfile);
    if (is_dir('/proc/' . $pid) && $pid) {
        die;
    }
}
file_put_contents(__DIR__ . '/beanstalk.pid', getmypid());

try {

    $config = include __DIR__ . "/../app/config/config.php";

    $loader = new \Phalcon\Loader();
    $loader->registerDirs($config->loader->dirs->toArray());
    $loader->registerNamespaces($config->loader->namespaces->toArray());
    $loader->register();
    
    include __DIR__ . "/../vendor/autoload.php";

    \ErrorPages::registerShutdown(true);

    include __DIR__ . "/../app/config/services.php";

    $queue = $di->get('queue');
    $serializer = new \SuperClosure\Serializer();
    
    while (($job = $queue->reserve())) {

        $serialized = $job->getBody();
        $unserialized = $serializer->unserialize($serialized);
        if (is_callable($unserialized)) {
            $unserialized($di);
        }
        $job->delete();
    }
} catch (\Exception $e) {
    \ErrorPages::handleException($e, true);
}
