<?php

/**
 * Base Controller
 *
 * @category
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace backend\controllers;

use models\Navbars;
use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{

    /**
     * Set title
     */
    public function initialize()
    {
        $this->tag->setTitle($this->config->application->name . ' - ');
        $this->view->navbarObject = new Navbars();
    }

}
