<?php

/**
 * Assets manager
 *
 * Config file example:
 *<code>
 * ['assets'       => [
 *      'frontend' => [
 *          'minify'     => false,
 *          'sourcePath' => __DIR__ . '/../modules/frontend/assets/',
 *          'cssPath'    => 'css/main.min.css',
 *          'cssPaths'   => [
 *              'css/materialize.css',
 *              'css/normalize.css',
 *              'css/style.css',
 *          ],
 *          'jsPath'     => 'js/main.min.js',
 *          'jsPaths'    => [
 *              'js/jquery-1.11.1.min.js',
 *              'js/materialize.js',
 *              'js/main.js',
 *          ],
 * ]]
 *</code>
 * @category
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace CodingBeard;

use Phalcon\Assets\Filters\Cssmin;
use Phalcon\Assets\Filters\Jsmin;
use Phalcon\Assets\Filters\None;
use Phalcon\DI;
use Phalcon\Mvc\User\Plugin;

class Assets extends Plugin
{

    /**
     * Public path for concated/minified css file
     * @var string
     */
    public $cssPath;

    /**
     * Public path for minified js file
     * @var string
     */
    public $jsPath;

    /**
     * Path for the json file which stores the current revision of css and js {'css':0,'js':0}
     * @var string
     */
    public $revisionPath;

    /**
     * Path for the private, raw assets
     * @var string
     */
    public $sourcePath;

    /**
     * Paths of css files relative to sourcePath
     * @var array
     */
    public $cssPaths;

    /**
     * Paths of js files relative to sourcePath
     * @var array
     */
    public $jsPaths;

    /**
     * Whether to minify the css or not (breaks inspect element with lots of files)
     * @var bool
     */
    public $minify;

    /**
     * Set DI and update properties from config file
     * @param DI $di
     * @param string $module
     */
    public function __construct($di, $module)
    {
        $this->_dependencyInjector = $di;
        foreach ($this->config->assets->$module as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Executed before Action method to set the default files
     */
    public function beforeExecuteRoute()
    {
        $css = $this->assets->collection('css');
        foreach ($this->cssPaths as $cssPath) {
            if (is_file($this->sourcePath . $cssPath)) {
                $css->addCss($this->sourcePath . $cssPath);
            }
        }

        $js = $this->assets->collection('js');
        foreach ($this->jsPaths as $jsPath) {
            if (is_file($this->sourcePath . $jsPath)) {
                $js->addJs($this->sourcePath . $jsPath);
            }
        }
    }

    /**
     * Executed after Action method to allow for extra assets to be added
     * Compares lastmod times of assets to public concat files to update or not
     */
    public function afterExecuteRoute()
    {
        $cssNeedsRefreshing = $jsNeedsRefreshing = false;

        if (is_file($this->config->application->publicDir . $this->cssPath)) {
            $cssLastModified = filemtime($this->config->application->publicDir . $this->cssPath);
            foreach ($this->assets->get('css')->getResources() as $resource) {
                if ($resource->getLocal()) {
                    if (($lastmod = filemtime($resource->getPath())) > $cssLastModified) {
                        $cssNeedsRefreshing = true;
                        $cssLastModified = $lastmod;
                    }
                }
            }
        }
        else {
            $cssNeedsRefreshing = true;
            $cssLastModified = time();
        }

        if (is_file($this->config->application->publicDir . $this->jsPath)) {
            $jsLastModified = filemtime($this->config->application->publicDir . $this->jsPath);
            foreach ($this->assets->get('js')->getResources() as $resource) {
                if ($resource->getLocal()) {
                    if (($lastmod = filemtime($resource->getPath())) > $jsLastModified) {
                        $jsNeedsRefreshing = true;
                        $jsLastModified = $lastmod;
                    }
                }
            }
        }
        else {
            $jsNeedsRefreshing = true;
            $jsLastModified = time();
        }

        if ($this->minify) {
            $filter = new Cssmin();
        }
        else {
            $filter = new None();
        }

        $this->assets->collection('css')
            ->join(true)
            ->setTargetPath($this->cssPath)
            ->setTargetUri($this->addRevision($this->cssPath, $cssLastModified))
            ->addFilter($filter);

        if ($cssNeedsRefreshing) {
            $this->assets->outputCss();
        }

        if ($this->minify) {
            $filter = new Jsmin();
        }
        else {
            $filter = new None();
        }

        $this->assets->collection('js')
            ->join(true)
            ->setTargetPath($this->jsPath)
            ->setTargetUri($this->addRevision($this->jsPath, $jsLastModified))
            ->addFilter($filter);

        if ($jsNeedsRefreshing) {
            $this->assets->outputJs();
        }
    }

    /**
     * Adds a revision number to a filename, requires a server config which serves E.G
     * style.min.css when style.min.24.css is requested
     * @param string $path
     * @param int $revision
     * @return string
     */
    public function addRevision($path, $revision)
    {
        $info = pathinfo($path);
        return $info['dirname'] . '/' . $info['filename'] . '.' . $revision . '.' . $info['extension'];
    }

}
