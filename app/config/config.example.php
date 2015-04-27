<?php

/**
 * Configuration file
 *
 * @category
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */
return new \Phalcon\Config([
    'database'     => [
        'adapter'  => 'Mysql',
        'host'     => 'localhost',
        'username' => '',
        'password' => '',
        'dbname'   => '',
    ],
    'application'  => [
        'name'       => 'Phalcon Skeleton',
        'domain'     => 'phalconskeleton.codingbeard.com',
        'https'      => false,
        'modelsDir'  => __DIR__ . '/../models/',
        'publicDir'  => __DIR__ . '/../../public/',
        'cacheDir'   => __DIR__ . '/../cache/',
        'baseUri'    => '/',
        'cipher'     => '',
        'showErrors' => true,
    ],
    'defaults'     => [
        'dateFormat'     => 'd/m/Y',
        'datetimeFormat' => 'd/m/Y H:i:s',
    ],
    'modules'      => [
        'uriPrefixes' => [
            'frontend' => '',
            'backend'  => '/admin',
        ],
        'files'       => [
            'frontend' => __DIR__ . '/../modules/frontend/Module.php',
            'backend'  => __DIR__ . '/../modules/backend/Module.php',
        ],
        'controllers' => [
            'frontend' => __DIR__ . '/../modules/frontend/controllers/',
            'backend'  => __DIR__ . '/../modules/backend/controllers/',
        ],
    ],
    'loader'       => [
        'dirs'       => [
            __DIR__ . '/../modules/',
            __DIR__ . '/../models/',
            __DIR__ . '/../plugins/',
            __DIR__ . '/../',
        ],
        'namespaces' => [
            'Tartan' => __DIR__ . '/../plugins/Tartan',
        ],
    ],
    'assets'       => [
        'frontend' => [
            'minify'     => false,
            'sourcePath' => __DIR__ . '/../modules/frontend/assets/',
            'cssPath'    => 'css/main.min.css',
            'cssPaths'   => [
                'css/materialize.css',
                'css/normalize.css',
                'css/font-awesome.min.css',
                'css/jquery.dataTables.css',
                'css/jquery-ui.css',
                'css/jquery-ui.theme.css',
                'css/jquery.tagit.css',
                'css/style.css',
            ],
            'jsPath'     => 'js/main.min.js',
            'jsPaths'    => [
                'js/jquery-1.11.1.min.js',
                'js/materialize.js',
                'js/jquery.dataTables.js',
                'js/jquery-ui.js',
                'js/tag-it.js',
                'js/cookiebanner.js',
                'js/main.js',
            ],
        ],
        'backend'  => [
            'minify'     => false,
            'sourcePath' => __DIR__ . '/../modules/backend/assets/',
            'cssPath'    => 'css/admin.min.css',
            'cssPaths'   => [
                '../../frontend/assets/css/materialize.css',
                '../../frontend/assets/css/normalize.css',
                '../../frontend/assets/css/font-awesome.min.css',
                '../../frontend/assets/css/jquery.dataTables.css',
                '../../frontend/assets/css/jquery-ui.css',
                '../../frontend/assets/css/jquery-ui.theme.css',
                '../../frontend/assets/css/jquery.tagit.css',
                'css/style.css',
            ],
            'jsPath'     => 'js/admin.min.js',
            'jsPaths'    => [
                '../../frontend/assets/js/jquery-1.11.1.min.js',
                '../../frontend/assets/js/materialize.js',
                '../../frontend/assets/js/jquery.dataTables.js',
                '../../frontend/assets/js/jquery-ui.js',
                '../../frontend/assets/js/tag-it.js',
                '../../frontend/assets/js/cookiebanner.js',
                'js/main.js',
            ],
        ],
    ],
    'view'         => [
        'frontend'  => [
            'viewsDir'      => __DIR__ . '/../modules/frontend/views/',
            'alwaysCompile' => true,
        ],
        'backend'   => [
            'viewsDir'      => __DIR__ . '/../modules/backend/views/',
            'alwaysCompile' => true,
        ],
        'filters'   => [
            ['number_format', 'number_format',],
            ['ucfirst', 'ucfirst',],
            ['strtotime', 'strtotime',],
        ],
        'functions' => [
            ['replace', 'str_replace',],
            ['substr', 'substr',],
            ['implode', 'implode',],
            ['explode', 'explode',],
            ['in_array', 'in_array',],
            ['unserialize', 'unserialize',],
            ['json_decode', 'json_decode',],
            ['json_encode', 'json_encode',],
            ['round', 'round',],
            ['nl2br', 'nl2br',],
            ['stripos', 'stripos',],
            ['slashes', 'addslashes',],
            ['preg_replace', 'preg_replace',],
        ],
    ],
    'captcha'      => [
        'publicKey'  => '',
        'privateKey' => '',
    ],
    'mail'         => [
        'mandrillKey' => '',
    ],
    'beanstalk'    => [
        'host' => '127.0.0.1',
        'key'  => 'phalconskeleton',
    ],
    'pagecontents' => [
        'allowVolt' => false,
        'voltDir'   => 'contents/',
    ],
]);
