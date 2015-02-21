<?php

/**
 * Configuration file
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */
return new \Phalcon\Config([
    'database' => [
        'adapter' => 'Mysql',
        'host' => 'localhost',
        'username' => '',
        'password' => '',
        'dbname' => '',
    ],
    'application' => [
        'name' => 'Skeleton',
        'modelsDir' => __DIR__ . '/../models/',
        'publicDir' => __DIR__ . '/../../public/',
        'modules' => [
            'frontend' => __DIR__ . '/../modules/frontend/Module.php',
            'backend' => __DIR__ . '/../modules/backend/Module.php'
        ],
        'baseUri' => '/',
        'cipher' => '',
        'showErrors' => true,
    ],
    'loader' => [
        'dirs' => [
            __DIR__ . '/../modules/',
            __DIR__ . '/../models/',
            __DIR__ . '/../plugins/',
            __DIR__ . '/../',
        ],
        'namespaces' => [
            'Tartan' => __DIR__ . '/../plugins/Tartan',
        ],
    ],
    'assets' => [
        'frontend' => [
            'minify' => false,
            'sourcePath' => __DIR__ . '/../assets/frontend/',
            'revisionPath' => __DIR__ . '/../modules/frontend/assetRevision',
            'cssPath' => 'css/main.min.css',
            'cssPaths' => [
                'css/normalize.css',
                'css/materialize.css',
                'css/font-awesome.min.css',
                'css/jquery.dataTables.css',
                'css/jquery-ui.css',
                'css/jquery-ui.theme.css',
                'css/jquery.tagit.css',
                'css/style.css',
            ],
            'jsPath' => 'js/main.min.js',
            'jsPaths' => [
                'js/jquery-1.11.1.min.js',
                'js/materialize.js',
                'js/jquery.dataTables.js',
                'js/jquery-ui.js',
                'js/tag-it.js',
                'js/cookiebanner.js',
                'js/main.js',
            ],
        ],
        'backend' => [
            'minify' => false,
            'sourcePath' => __DIR__ . '/../assets/',
            'revisionPath' => __DIR__ . '/../modules/backend/assetRevision',
            'cssPath' => 'css/admin.min.css',
            'cssPaths' => [
                'frontend/css/normalize.css',
                'backend/css/materialize.css',
                'frontend/css/font-awesome.min.css',
                'frontend/css/jquery.dataTables.css',
                'frontend/css/jquery-ui.css',
                'frontend/css/jquery-ui.theme.css',
                'frontend/css/jquery.tagit.css',
                'backend/css/style.css',
            ],
            'jsPath' => 'js/admin.min.js',
            'jsPaths' => [
                'frontend/js/jquery-1.11.1.min.js',
                'frontend/js/materialize.js',
                'frontend/js/jquery.dataTables.js',
                'frontend/js/jquery-ui.js',
                'frontend/js/tag-it.js',
                'frontend/js/cookiebanner.js',
                'backend/js/main.js',
            ],
        ],
    ],
    'view' => [
        'frontend' => [
            'viewsDir' => __DIR__ . '/../modules/frontend/views/',
            'compileDir' => __DIR__ . '/../cache/',
            'alwaysCompile' => true,
            'filters' => [
                ['number_format', 'number_format'],
                ['ucfirst', 'ucfirst'],
                ['strtotime', 'strtotime'],
            ],
            'functions' => [
                ['replace', 'str_replace'],
                ['substr', 'substr'],
                ['implode', 'implode'],
                ['explode', 'explode'],
                ['in_array', 'in_array'],
                ['unserialize', 'unserialize'],
                ['json_decode', 'json_decode'],
                ['json_encode', 'json_encode'],
                ['round', 'round'],
                ['nl2br', 'nl2br'],
                ['stripos', 'stripos'],
            ]
        ],
        'backend' => [
            'viewsDir' => __DIR__ . '/../modules/backend/views/',
            'compileDir' => __DIR__ . '/../cache/',
            'alwaysCompile' => true,
            'filters' => [
                ['number_format', 'number_format'],
                ['ucfirst', 'ucfirst'],
                ['strtotime', 'strtotime'],
            ],
            'functions' => [
                ['replace', 'str_replace'],
                ['substr', 'substr'],
                ['implode', 'implode'],
                ['explode', 'explode'],
                ['in_array', 'in_array'],
                ['unserialize', 'unserialize'],
                ['json_decode', 'json_decode'],
                ['json_encode', 'json_encode'],
                ['round', 'round'],
                ['nl2br', 'nl2br'],
                ['stripos', 'stripos'],
            ]
        ],
    ],
    'captcha' => [
        'publicKey' => '',
        'privateKey' => '',
    ],
    'mail' => [
        'domain' => '',
        'mandrillKey' => '',
    ],
 /*
  '' => array(
  '' => '',
  ),
 */
]);
