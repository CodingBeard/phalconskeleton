<?php

/**
 * Bootstrap
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
try {

    $config = include __DIR__ . "/../app/config/config.php";

    $loader = new \Phalcon\Loader();
    $loader->registerDirs($config->loader->dirs->toArray());
    $loader->registerNamespaces($config->loader->namespaces->toArray());
    $loader->register();
    
    \ErrorPages::registerShutdown($config->application->showErrors);

    include __DIR__ . "/../app/config/services.php";

    $application = new \Phalcon\Mvc\Application($di);

    $modules = [];
    foreach ($config->application->modules as $name => $path) {
        $modules[$name] = [
            'className' => "{$name}\Module",
            'path' => $path
        ];
    }
    $application->registerModules($modules);

    echo $application->handle()->getContent();
} catch (\Exception $e) {
    \ErrorPages::handleException($e, $config->application->showErrors);
}
