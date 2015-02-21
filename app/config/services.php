<?php

/**
 * Services setup
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */
use Phalcon\DI\FactoryDefault,
    Phalcon\Mvc\Router,
    Phalcon\Mvc\Url as UrlResolver,
    Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter,
    Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter,
    Phalcon\Session\Adapter\Files as SessionAdapter;


$di = new FactoryDefault();

$di->set('config', $config);

$di->set('crypt', function() use ($config)
{
    $crypt = new Phalcon\Crypt();
    $crypt->setKey($config->application->cipher);
    return $crypt;
});

$di->set('cookies', function()
{
    $cookies = new Phalcon\Http\Response\Cookies();
    $cookies->useEncryption(true);
    return $cookies;
});

/**
 * Set up multi-module routing
 */
$di->set('router', function ()
{
    $router = new Router(false);
    $router->setDefaultModule("frontend");
    $router->add('(/)?', [
        'module' => "frontend",
        'action' => "index",
        'params' => "index"
    ]);
    $router->add('/:controller(/)?', [
        'module' => "frontend",
        'controller' => 1,
        'action' => "index"
    ]);
    $router->add('/:controller/:action(/)?', [
        'module' => "frontend",
        'controller' => 1,
        'action' => 2
    ]);
    $router->add('/:controller/:action/:params(/)?', [
        'module' => "frontend",
        'controller' => 1,
        'action' => 2,
        'params' => 3
    ]);
    $router->add('/(admin(/)?)', [
        'module' => "backend",
        'action' => "index",
        'params' => "index"
    ]);
    $router->add('/admin/:controller(/)?', [
        'module' => "backend",
        'controller' => 1,
        'action' => "index"
    ]);
    $router->add('/admin/:controller/:action(/)?', [
        'module' => "backend",
        'controller' => 1,
        'action' => 2
    ]);
    $router->add('/admin/:controller/:action/:params(/)?', [
        'module' => "backend",
        'controller' => 1,
        'action' => 2,
        'params' => 3
    ]);

    return $router;
});

$di->set('url', function () use ($config)
{
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
}, true);

$di->set('db', function () use ($config)
{
    return new DbAdapter([
        'adapter' => $config->database->adapter,
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname,
        'port' => $config->database->port,
    ]);
});

$di->set('modelsMetadata', function ()
{
    return new MetaDataAdapter();
});

$di->set('session', function ()
{
    $session = new SessionAdapter();
    $session->start();
    return $session;
}, true);

$di->set('flashSession', function()
{
    $flash = new \Phalcon\Flash\Session([
        'error' => 'alert alert-danger alert-dismissible',
        'success' => 'alert alert-success alert-dismissible',
        'notice' => 'alert alert-info alert-dismissible',
        'warning' => 'alert alert-warning alert-dismissible',
    ]);
    return $flash;
});

$di->set('security', function()
{
    $security = new \Phalcon\Security();
    $security->setWorkFactor(12);
    return $security;
}, true);

$di->set('mandrill', function() use ($config)
{
    return new \Tartan\Mandrill($config->mail->mandrillKey);
});

$di->set('auth', function() use ($di)
{
    return $di->getSession()->has('auth') ? $di->getSession()->get('auth') : new \Auth();
}, true);

$di->set('showErrors', function() use ($config)
{
    return $config->application->showErrors;
}, true);

$di->set('formBuilder', function() use ($di)
{
    return new \Forms\FormBuilder($di);
}, true);

$di->set('captcha', function() use ($config)
{
    return new \Captcha($config->captcha->publicKey, $config->captcha->privateKey);
}, true);