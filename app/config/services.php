<?php

use CodingBeard\Acl;
use CodingBeard\Assets;
use CodingBeard\Auth;
use CodingBeard\BeanstalkWithSerialize;
use CodingBeard\DispatchingExceptionHandler;
use CodingBeard\Forms\FormBuilder;
use CodingBeard\Emails\SiteEmails;
use Google\Captcha;
use Phalcon\Cache\Backend\File;
use Phalcon\Cache\Frontend\Output;
use Phalcon\Crypt;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Di;
use Phalcon\DI\FactoryDefault;
use Phalcon\Filter;
use Phalcon\Flash\Session;
use Phalcon\Http\Response\Cookies;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Model\Metadata\Memory;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Volt;
use Phalcon\Security;
use Phalcon\Session\Adapter\Files;
use Tartan\Mandrill;

/**
 * Services setup
 *
 * @category
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */
if (!$di) {
    $di = new Di();

    $di->setDefault(new FactoryDefault());
}

/**
 * Variables
 */
$di->set('config', $config);

$module = $router->getModuleName();

$di->set('module', function () use ($module) {
    return $module;
});

$di->set('showErrors', function () use ($config) {
    return $config->application->showErrors;
}, true);


/**
 * Routing, Dispatching
 */
$di->set('router', $router);

$di->set('dispatcher', function () use ($di, $module) {
    $eventsManager = $di->getShared('eventsManager');

    $errors = new DispatchingExceptionHandler();
    $eventsManager->attach('dispatch', $errors);

    $security = new Acl($di, $module);
    $eventsManager->attach('dispatch', $security);

    $assets = new Assets($di, $module);
    $eventsManager->attach('dispatch', $assets);

    /*
     * Filter and standardize controller/action names to make case insensitive and allow for hyphens
     */
    $eventsManager->attach("dispatch:beforeDispatchLoop", function ($event, $dispatcher) {
        $controller = strtolower(str_replace('-', '', $dispatcher->getControllerName()));
        $action = strtolower(str_replace('-', '', $dispatcher->getActionName()));
        $dispatcher->setControllerName($controller);
        $dispatcher->setActionName($action);
    });

    $dispatcher = new Dispatcher();
    $dispatcher->setDefaultNamespace("{$module}\controllers");
    $dispatcher->setEventsManager($eventsManager);
    return $dispatcher;
});


/**
 * Database
 */
$di->set('db', function () use ($config) {
    return new Mysql([
        'adapter' => $config->database->adapter,
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname
    ]);
});

$di->set('modelsMetadata', function () {
    return new Memory();
});


/*
 * Views
 */
$volt = function ($view, $di) use ($config, $module) {
    $volt = new Volt($view, $di);
    $volt->setOptions([
        'compiledPath' => $config->application->cacheDir,
        'compiledSeparator' => '.',
        'compileAlways' => $config->view[$module]->alwaysCompile,
        'prefix' => 'cache'
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

$view = function () use ($volt, $config, $module) {
    $view = new View();
    $view->setViewsDir($config->view[$module]->viewsDir);
    $view->registerEngines([
        '.volt' => $volt,
        '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
    ]);
    return $view;
};

$di->set('view', $view, true);

$di->set('formview', $view, true);

$di->set('compiler', function () use ($volt) {
    return $volt()->getCompiler();
}, true);

$di->set('url', function () use ($config) {
    $url = new Url();
    $url->setBaseUri($config->application->baseUri);

    return $url;
}, true);

$di->set('viewCache', function () use ($config) {
    return new File(new Output(["lifetime" => 86400]), [
        "prefix" => "cache.view.",
        "cacheDir" => $config->application->cacheDir
    ]);
}, true);


/**
 * Misc
 */

$di->set('security', function () {
    $security = new Security();
    $security->setWorkFactor(12);
    return $security;
}, true);

$di->set('filter', function () {
    return new Filter();
}, true);

$di->set('crypt', function () use ($config) {
    $crypt = new Crypt();
    $crypt->setKey($config->application->cipher);
    return $crypt;
});

$di->set('cookies', function () {
    $cookies = new Cookies();
    $cookies->useEncryption(false);
    return $cookies;
});


/**
 * Session
 */
$di->set('session', function () {
    $session = new Files();
    $session->start();
    return $session;
}, true);

$di->set('flashSession', function () {
    $flash = new Session([
        'error' => 'alert alert-danger alert-dismissible',
        'success' => 'alert alert-success alert-dismissible',
        'notice' => 'alert alert-info alert-dismissible',
        'warning' => 'alert alert-warning alert-dismissible',
    ]);
    return $flash;
});


/**
 * Plugins
 */
$di->set('mandrill', function () use ($config) {
    return new Mandrill($config->mail->mandrillKey);
});

$di->set('auth', function () {
    return new Auth();
}, true);

$di->set('form', function () use ($di) {
    return new FormBuilder($di);
}, true);

$di->set('captcha', function () use ($config) {
    return new Captcha($config->captcha->publicKey, $config->captcha->privateKey);
}, true);

$di->set('emails', function () {
    return new SiteEmails();
}, true);

$di->set('queue', function () use ($config) {
    return new BeanstalkWithSerialize(array(
        'host' => $config->beanstalk->host
    ));
}, true);

