<?php

/**
 * Base Controller
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */

namespace backend\controllers;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{

    /**
     * Set title
     */
    public function initialize()
    {
        $this->tag->setTitle($this->config->application->name . ' - ');
        $this->view->navbarObject = new \Navbars();
    }

}
