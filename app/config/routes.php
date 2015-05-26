<?php

/**
 * Custom routes file
 *
 * @category
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */
/**
 * Usage: [$uriPattern => $array/$shortSyntax], http://docs.phalconphp.com/en/latest/reference/routing.html
 * Short example: ['uri' => '$moduleName::$controllerName::$actionName']
 * Long example ['uri/:params' => [
 *  'module' => $moduleName,
 *  'controller' => $controllerName,
 *  'action' => $actionName,
 *  'params' => 1
 * ]]
 */
$routes = [
    'register' => 'frontend::account::register',
    'verifyemail' => 'frontend::account::verifyemail',
    'login' => 'frontend::session::login',
    'logout' => 'frontend::session::logout',
];

$module = "frontend";

$router = new \Phalcon\Mvc\Router(false);
$router->setDefaultModule($module);

/**
 * Add variable routing for multiple modules and custom url prefixes
 * And work out what module we're on. $module is used in the services
 */
foreach ($config->modules->uriPrefixes as $moduleName => $prefix) {
    $router->add("{$prefix}(/)?", [
        'module' => $moduleName,
        'controller' => 'index',
        'action' => 'index'
    ]);
    $router->add("{$prefix}/:controller(/)?", [
        'module' => $moduleName,
        'controller' => 1,
        'action' => 'index'
    ]);
    $router->add("{$prefix}/:controller/:action(/)?", [
        'module' => $moduleName,
        'controller' => 1,
        'action' => 2
    ]);
    $router->add("{$prefix}/:controller/:action/:params(/)?", [
        'module' => $moduleName,
        'controller' => 1,
        'action' => 2,
        'params' => 3
    ]);

    if (stripos($_SERVER['REQUEST_URI'], $prefix) === 0) {
        $module = $moduleName;
    }
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