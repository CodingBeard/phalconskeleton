<?php

error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

/**
 * Bootstrap
 *
 * @category
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */
use CodingBeard\ErrorHandler;
use Phalcon\Loader;
use Phalcon\Mvc\Application;

try {

    $config = include __DIR__ . "/../app/config/config.php";

    $router = include __DIR__ . "/../app/config/routes.php";

    $loader = new Loader();
    $loader->registerDirs($config->loader->dirs->toArray());
    $loader->registerNamespaces($config->loader->namespaces->toArray());
    $loader->register();

    include __DIR__ . "/../vendor/autoload.php";

    ErrorHandler::setErrorHandler($config->application->showErrors);

    include __DIR__ . "/../app/config/services.php";

    $application = new Application($di);

    $modules = [];
    foreach ($config->modules->files as $name => $path) {
        $modules[$name] = [
            'className' => "{$name}\Module",
            'path' => $path
        ];
    }
    $application->registerModules($modules);

    echo $application->handle()->getContent();
} catch (Exception $e) {
    ErrorHandler::handleException($e, $config->application->showErrors);
}
ErrorHandler::printErrors($config->application->showErrors);