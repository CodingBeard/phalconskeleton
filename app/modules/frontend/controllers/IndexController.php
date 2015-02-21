<?php

/**
 * Index controller, url: / | /index/
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */

namespace frontend\controllers;

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
