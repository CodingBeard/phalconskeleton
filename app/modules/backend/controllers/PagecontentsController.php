<?php

/**
 * Page Contents controller, url: /admin/pagecontents/view | /admin/(varies)
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace backend\controllers;

use models\Pages;

class PagecontentsController extends ControllerBase
{

    /**
     * Display a page
     * @param int $page_id
     */
    public function viewAction($page_id)
    {
        $page = Pages::findFirstById($page_id);
        if ($page) {
            $this->tag->appendTitle($page->title);
            $this->view->page = $page;
        }
    }

}
