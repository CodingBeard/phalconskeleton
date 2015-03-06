<?php

/**
 * Cache controller, url: /admin/cache
 *
 * @category
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace backend\controllers;

class CacheController extends ControllerBase
{

    /**
     * Clear the cache
     */
    public function clearAction()
    {
        foreach (glob($this->config->application->cacheDir . '*') as $file) {
            if (stripos(basename($file), 'cache') !== false) {
                unlink($file);
            }
        }
        $this->auth->redirect('admin', 'success', 'Cache Cleared');
    }

}
