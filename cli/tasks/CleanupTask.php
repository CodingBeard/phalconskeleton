<?php

/**
 * Clean up task
 *
 * @category
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */
class CleanupTask extends \Phalcon\CLI\Task
{

    public function mainAction()
    {
        $authtokens = \Authtokens::find([
            'expires < :a:',
            'bind' => ['a' => date('Y-m-d H:i:s', strtotime('-1 day'))]
        ]);
        if ($authtokens) {
            $authtokens->delete();
        }
    }

}
