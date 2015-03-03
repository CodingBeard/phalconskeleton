<?php

/**
 * Phalcon Module
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace backend;

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
    public $module = 'backend';

    /**
     * Autoloader
     */
    public function registerAutoloaders(\Phalcon\DiInterface $di = NULL)
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
    public function registerServices(\Phalcon\DiInterface $di = NULL)
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
            
            $errors = new \DispatchingExceptionHandler();
            $eventsManager->attach('dispatch', $errors);
            
            $security = new \Security($di, $this->module);
            $eventsManager->attach('dispatch', $security);

            $assets = new \Assets($di, $this->module);
            $eventsManager->attach('dispatch', $assets);

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
        $volt = function ($view, $di) use ($config)
        {
            $volt = new VoltEngine($view, $di);
            $volt->setOptions([
                'compiledPath' => $config->view[$this->module]->compileDir,
                'compiledSeparator' => '_',
                'compileAlways' => $config->view[$this->module]->alwaysCompile
            ]);
            $compiler = $volt->getCompiler();
            $viewConfig = $config->view->toArray();
            foreach ($viewConfig['filters'] as $filter) {
                $compiler->addFilter($filter[0], $filter[1]);
            }
            foreach ($viewConfig['functions'] as $function) {
                $compiler->addFunction($function[0], $function[1]);
            }
            return $volt;
        };
        $view = function () use ($di, $volt, $config)
        {
            $view = new View();
            $view->setViewsDir($config->view[$this->module]->viewsDir);
            $view->registerEngines([
            '.volt' => $volt,
            '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
            ]);
            return $view;
        };
        $di->set('view', $view, true);
        $di->set('formview', $view, true);
        $di->set('compiler', function () use ($volt)
        {
            return $volt()->getCompiler();
        }, true);
    }

}
