<?php

/**
 * Phalcon Module
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */

namespace frontend;

use Phalcon\Loader,
    Phalcon\Mvc\Dispatcher,
    Phalcon\Mvc\View,
    Phalcon\Mvc\View\Engine\Volt as VoltEngine,
    Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface
{
    /**
     * Name of Module
     * @var string 
     */
    public $module = 'frontend';

    /**
     * Autoloader
     */
    public function registerAutoloaders()
    {
        $loader = new Loader();
        $loader->registerNamespaces([
            "{$this->module}\controllers" => __DIR__ . '/controllers/'
        ]);
        $loader->register();
    }

    /**
     * Register services for the module
     * @param \Phalcon\DI $di
     */
    public function registerServices($di)
    {
        $config = $di->getShared('config');

        $di->set('module', function()
        {
            return $this->module;
        });

        $di->set('dispatcher', function() use ($di)
        {
            $eventsManager = $di->getShared('eventsManager');

            /*
             * Add plugins to the dispatcher to listen for events
             */
            $security = new \Security($di, $this->module);
            $eventsManager->attach('dispatch', $security);

            $assets = new \Assets($di, $this->module);
            $eventsManager->attach('dispatch', $assets);

            $errors = new \ErrorPages($di);
            $eventsManager->attach('dispatch', $errors);

            /*
             * Filter and standardize controller/action names to make case insensitive and allow for hyphens
             */
            $eventsManager->attach("dispatch:beforeDispatchLoop", function($event, $dispatcher) use ($di)
            {
                $controller = strtolower(str_replace('-', '', $dispatcher->getControllerName()));
                $action = strtolower(str_replace('-', '', $dispatcher->getActionName()));
                $dispatcher->setControllerName($controller);
                $dispatcher->setActionName($action);
            });

            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace("{$this->module}\controllers");
            $dispatcher->setEventsManager($eventsManager);
            return $dispatcher;
        });

        /*
         * Set up Volt Engine
         */
        $di->set('view', function () use ($di, $config)
        {
            $view = new View();
            $view->setViewsDir($config->view[$this->module]->viewsDir);
            $view->registerEngines([
                '.volt' => function ($view, $di) use ($config)
                {
                    $volt = new VoltEngine($view, $di);
                    $volt->setOptions([
                        'compiledPath' => $config->view[$this->module]->compileDir,
                        'compiledSeparator' => '_',
                        'compileAlways' => $config->view[$this->module]->alwaysCompile
                    ]);
                    $compiler = $volt->getCompiler();
                    foreach ($config->view[$this->module]->filters as $filter) {
                        $compiler->addFilter($filter[0], $filter[1]);
                    }
                    foreach ($config->view[$this->module]->functions as $function) {
                        $compiler->addFunction($function[0], $function[1]);
                    }
                    return $volt;
                },
                '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
            ]);
            return $view;
        }, true);
    }

}
