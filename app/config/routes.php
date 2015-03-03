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
return [
    'register' => 'frontend::account::register',
    'verifyemail' => 'frontend::account::verifyemail',
    'login' => 'frontend::session::login',
    'logout' => 'frontend::session::logout',
];
