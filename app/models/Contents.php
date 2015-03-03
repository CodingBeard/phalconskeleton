<?php

/**
 * Contents
 * 
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */
class Contents extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var int
     */
    public $ordering;

    /**
     *
     * @var int
     */
    public $width;

    /**
     *
     * @var int
     */
    public $offset;

    /**
     *
     * @var string
     */
    public $content;

    /**
     *
     * @var integer
     */
    public $page_id;

    /**
     *
     * @var integer
     */
    public $parent_id;

    /**
     * Save and execute the contents, returning the result
     * @return string
     */
    public function getContent()
    {
        $di = $this->getDI();
        if ($di->get('config')->pagecontents->allowVolt) {
            $file = $di->get('config')->pagecontents->voltDir . "content-{$this->id}.volt";
            $view = $di->get('view');
            file_put_contents($view->getViewsDir() . $file, $this->content);
            return $view->partial(substr($file, 0, -5));
        }
        else {
            return $this->content;
        }
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->keepSnapshots(true);
        $this->addBehavior(new \Blameable());
        $this->useDynamicUpdate(true);
        $this->hasMany('id', 'Contents', 'parent_id', ['alias' => 'Children']);
        $this->belongsTo('parent_id', 'Contents', 'id', ['alias' => 'Parent']);
        $this->belongsTo('page_id', 'Pages', 'id', ['alias' => 'Pages']);
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return [
            'id' => 'id',
            'ordering' => 'ordering',
            'width' => 'width',
            'offset' => 'offset',
            'content' => 'content',
            'page_id' => 'page_id',
            'parent_id' => 'parent_id'
        ];
    }

}
