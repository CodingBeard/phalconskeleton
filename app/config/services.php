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

if (!$di) {
    $di = new Phalcon\Di();

    $di->setDefault(new FactoryDefault());
}
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
    $cookies->useEncryption(false);
    return $cookies;
});

/**
 * Set up multi-module routing
 */
$di->set('router', function () use ($config, $routes)
{
    $router = new Router(false);
    $router->setDefaultModule("frontend");

    /**
     * Add variable routing for multiple modules and custom url prefixes
     */
    foreach ($config->modules->uriPrefixes as $module => $prefix) {
        $router->add("{$prefix}(/)?", [
            'module' => $module,
            'controller' => 'index',
            'action' => 'index'
        ]);
        $router->add("{$prefix}/:controller(/)?", [
            'module' => $module,
            'controller' => 1,
            'action' => 'index'
        ]);
        $router->add("{$prefix}/:controller/:action(/)?", [
            'module' => $module,
            'controller' => 1,
            'action' => 2
        ]);
        $router->add("{$prefix}/:controller/:action/:params(/)?", [
            'module' => $module,
            'controller' => 1,
            'action' => 2,
            'params' => 3
        ]);
    }

    /**
     * Allow for hyphens and case insensitivity in static routes
     * Short syntax doesn't work for params, so we add that on top
     * Static routes come after variable ones as router has reverese priority
     */
    foreach ($routes as $uri => $route) {
        $router->add('#^/' . implode('-?', str_split($uri)) . '$#i', $route);

        $split = explode('::', $route);
        $router->add('#^/' . implode('-?', str_split($uri)) . '(/.*)*$#i', [
            'module' => $split[0],
            'controller' => $split[1],
            'action' => $split[2],
            'params' => 1
        ]);
    }
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

$di->set('filter', function()
{
    return new \Phalcon\Filter();
}, true);

$di->set('mandrill', function() use ($config)
{
    return new \Tartan\Mandrill($config->mail->mandrillKey);
});

$di->set('auth', function()
{
    return new Auth();
}, true);

$di->set('showErrors', function() use ($config)
{
    return $config->application->showErrors;
}, true);

$di->set('form', function() use ($di)
{
    return new \Forms\FormBuilder($di);
}, true);

$di->set('captcha', function() use ($config)
{
    return new \Captcha($config->captcha->publicKey, $config->captcha->privateKey);
}, true);

$di->set('emails', function()
{
    return new \Emails\SiteEmails();
}, true);

$di->set('queue', function() use ($config)
{
    return new \BeanstalkWithSerialize(array(
        'host' => $config->beanstalk->host
    ));
}, true);

