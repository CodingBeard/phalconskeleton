<?php

/**
 * Index controller, url: /terms
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */

namespace frontend\controllers;

class SiteController extends ControllerBase
{

    /**
     * Terms page
     */
    public function termsAction()
    {
        $this->tag->appendTitle("Terms of Service");
    }

    /**
     * Privacy policy page
     */
    public function privacyAction()
    {
        $this->tag->appendTitle("Privacy policy");
    }

    /**
     * Credits page
     */
    public function creditsAction()
    {
        $this->tag->appendTitle("Credits");
    }

}
