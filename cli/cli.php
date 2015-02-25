<?php

/**
 * Cli
 *
 * @category 
 * @package 
 * @author Tim Marshall
 * @copyright (c) 2014, Tim Marshall
 * @version 
 */
use Phalcon\DI\FactoryDefault\CLI as CliDI,
    Phalcon\CLI\Console as ConsoleApp;

try {
    $config = include __DIR__ . "/../app/config/config.php";

    $loader = new \Phalcon\Loader();
    $config->loader->dirs[] = __DIR__ . '/tasks/';
    $loader->registerDirs($config->loader->dirs->toArray());
    $loader->registerNamespaces($config->loader->namespaces->toArray());
    $loader->register();

    include __DIR__ . "/../vendor/autoload.php";

    register_shutdown_function(function () use ($argv)
    {
        unlink(__DIR__ . '/pids/' . $argv[1] . '.pid');
    });

    $di = new CliDI();

    $di->set('config', $config);
    $di->set('db', function () use ($config)
    {
        return new Phalcon\Db\Adapter\Pdo\Mysql([
            'adapter' => $config->database->adapter,
            'host' => $config->database->host,
            'username' => $config->database->username,
            'password' => $config->database->password,
            'dbname' => $config->database->dbname,
        ]);
    });
    $di->set('mandrill', function() use ($config)
    {
        return new \Tartan\Mandrill($config->mail->mandrillKey);
    });
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
    $di->set('view', function () use ($config)
    {
        $view = new \Phalcon\Mvc\View();
        $view->setViewsDir($config->view->frontend->viewsDir);
        $view->registerEngines([
            '.volt' => function ($view, $di) use ($config)
            {
                $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);
                $volt->setOptions([
                    'compiledPath' => __DIR__ . '/cache/',
                    'compiledSeparator' => '_',
                    'compileAlways' => true
                ]);
                $compiler = $volt->getCompiler();
                foreach ($config->view->frontend->filters as $filter) {
                    $compiler->addFilter($filter[0], $filter[1]);
                }
                foreach ($config->view->frontend->functions as $function) {
                    $compiler->addFunction($function[0], $function[1]);
                }
                return $volt;
            }
        ]);
        return $view;
    }, true);

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
    \ErrorPages::handleException($e, true);
    file_put_contents(__DIR__ . '/../logs/clierror.log', $e->getMessage() . PHP_EOL . $e->getTraceAsString());
    exit(255);
}
