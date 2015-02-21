<?php

/**
 * Index controller, url: /admin/
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */

namespace backend\controllers;

class IndexController extends ControllerBase
{

    /**
     * Index page
     */
    public function indexAction()
    {
        $this->tag->appendTitle("Home");
    }

}
