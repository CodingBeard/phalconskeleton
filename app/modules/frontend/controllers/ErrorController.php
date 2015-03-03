<?php

/**
 * Error controller, url: /error/
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2014, Tim Marshall
 * @license New BSD License
 */

namespace frontend\controllers;

class ErrorController extends ControllerBase
{

    /**
     * Index, show generic 500 type error page
     */
    public function indexAction()
    {
        $this->tag->appendTitle('500');
        $this->response->setHeader(500, 'Error');
    }

    /**
     * Not found, show 404 page
     */
    public function notFoundAction()
    {
        $this->tag->appendTitle('404');
        $this->response->setHeader(404, 'Not Found');
    }

}
