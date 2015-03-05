<?php

/**
 * Cli
 *
 * @category
 * @package
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2014, Tim Marshall
 * @license New BSD License
 */
use CodingBeard\BeanstalkWithSerialize;
use CodingBeard\Emails\SiteEmails;
use Phalcon\DI\FactoryDefault\CLI as CliDI,
    Phalcon\CLI\Console as ConsoleApp;
use Phalcon\Mvc\View;

try {
    $config = include __DIR__ . "/../app/config/config.php";

    $dirs = $config->loader->dirs->toArray();
    $dirs[] = __DIR__ . '/tasks/';

    $loader = new \Phalcon\Loader();
    $loader->registerDirs($dirs);
    $loader->registerNamespaces($config->loader->namespaces->toArray());
    $loader->register();

    include __DIR__ . "/../vendor/autoload.php";

    $di = new CliDI();

    $di->set('config', $config);
    $di->set('db', function () use ($config) {
        return new Phalcon\Db\Adapter\Pdo\Mysql([
            'adapter' => $config->database->adapter,
            'host' => $config->database->host,
            'username' => $config->database->username,
            'password' => $config->database->password,
            'dbname' => $config->database->dbname,
        ]);
    });
    $di->set('mandrill', function () use ($config) {
        return new \Tartan\Mandrill($config->mail->mandrillKey);
    });
    $di->set('emails', function () {
        return new SiteEmails();
    }, true);
    $di->set('queue', function () use ($config) {
        return new BeanstalkWithSerialize(array(
            'host' => $config->beanstalk->host
        ));
    }, true);
    $volt = function ($view, $di) use ($config) {
        $volt = new Volt($view, $di);
        $volt->setOptions([
            'compiledPath' => $config->application->cacheDir,
            'compiledSeparator' => '.',
            'compileAlways' => true,
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

    $view = function () use ($volt, $config) {
        $view = new View();
        $view->registerEngines([
            '.volt' => $volt,
            '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
        ]);
        return $view;
    };

    $di->set('view', $view, true);

    /**
     * Process the console arguments
     */
    $arguments = array();
    foreach ($argv as $k => $arg) {
        if ($k == 1) {
            $arguments['task'] = $arg;
        }
        elseif ($k == 2) {
            $arguments[] = $arg;
        }
        elseif ($k >= 3) {
            $arguments[] = $arg;
        }
    }

    /**
     * Create a console application
     */
    $console = new ConsoleApp();
    $di->setShared('console', $console);
    $console->setDI($di);


    define('CURRENT_TASK', (isset($argv[1]) ? $argv[1] : null));
    define('CURRENT_ACTION', (isset($argv[2]) ? $argv[2] : null));


    $console->handle($arguments);
} catch (Exception $e) {
    file_put_contents(__DIR__ . '/../logs/clierror.log', $e->getMessage() . PHP_EOL . $e->getTraceAsString());
    exit(255);
}
