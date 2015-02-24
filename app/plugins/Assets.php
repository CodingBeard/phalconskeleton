<?php

/**
 * Assets manager
 * 
 * Config file example:
    'assets' => [
        'frontend' => [
            'minify' => false,
            'sourcePath' => __DIR__ . '/../assets/',
            'revisionPath' => __DIR__ . '/../modules/frontend/assetRevision',
            'cssPath' => 'css/main.min.css',
            'cssPaths' => [
                'css/normalize.css',
                'css/style.css',
            ],
            'jsPath' => 'js/main.min.js',
            'jsPaths' => [
                'js/jquery-1.11.1.min.js',
                'js/main.js',
            ],
        ],
    ],
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */

class Assets extends Phalcon\Mvc\User\Plugin
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
     * @param \Phalcon\DI $di
     * @param string $module
     */
    public function __construct($di, $module)
    {
        $this->_dependencyInjector = $di;
        foreach ($this->config->assets->$module as $key => $value) {
            $this->$key = $value;
        }
        if (!is_file($this->revisionPath)) {
            file_put_contents($this->revisionPath, json_encode(['css' => 0, 'js' => 0]));
        }
    }

    /**
     * Executed before Action method to set the default files
     */
    public function beforeExecuteRoute()
    {
        $css = $this->assets->collection('css');
        $css->setSourcePath($this->sourcePath);
        foreach ($this->cssPaths as $cssPath) {
            $css->addCss($cssPath);
        }

        $js = $this->assets->collection('js');
        $js->setSourcePath($this->sourcePath);
        foreach ($this->jsPaths as $jsPath) {
            $js->addJs($jsPath);
        }
    }

    /**
     * Executed after Action method to allow for extra assets to be added
     * Compares lastmod times of assets to public concat files to update or not
     */
    public function afterExecuteRoute()
    {

        $cssNeedsRefreshing = $jsNeedsRefreshing = false;

        $cssLastModified = filemtime($this->config->application->publicDir . $this->cssPath);
        foreach ($this->assets->get('css')->getResources() as $resource) {
            if ($resource->getLocal()) {
                if (filemtime($this->sourcePath . $resource->getPath()) > $cssLastModified) {
                    $cssNeedsRefreshing = true;
                }
            }
        }

        $jsLastModified = filemtime($this->config->application->publicDir . $this->jsPath);
        foreach ($this->assets->get('js')->getResources() as $resource) {
            if ($resource->getLocal()) {
                if (filemtime($this->sourcePath . $resource->getPath()) > $jsLastModified) {
                    $jsNeedsRefreshing = true;
                }
            }
        }

        $revision = json_decode(file_get_contents($this->revisionPath));

        if ($cssNeedsRefreshing) {
            file_put_contents($this->revisionPath, json_encode(['css' => ++$revision->css, 'js' => $revision->js]));
        }

        if ($this->minify) {
            $filter = new \Phalcon\Assets\Filters\Cssmin();
        }
        else {
            $filter = new \Phalcon\Assets\Filters\None();
        }

        $this->assets->collection('css')
        ->join(true)
        ->setTargetPath($this->cssPath)
        ->setTargetUri($this->addRevision($this->cssPath, $revision->css))
        ->addFilter($filter);
        
        if ($cssNeedsRefreshing) {
            $this->assets->outputCss();
        }

        if ($jsNeedsRefreshing) {
            file_put_contents($this->revisionPath, json_encode(['css' => $revision->css, 'js' => ++$revision->js]));
        }

        if ($this->minify) {
            $filter = new \Phalcon\Assets\Filters\Jsmin();
        }
        else {
            $filter = new \Phalcon\Assets\Filters\None();
        }
        
        $this->assets->collection('js')
        ->join(true)
        ->setTargetPath($this->jsPath)
        ->setTargetUri($this->addRevision($this->jsPath, $revision->js))
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
        $split = explode('.', $path);
        $extension = array_pop($split);
        return str_replace('.' . $extension, '.' . $revision . '.' . $extension, $path);
    }

}
