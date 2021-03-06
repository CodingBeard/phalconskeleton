<?php

/**
 * Pages
 * 
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace models;

use CodingBeard\Blameable;
use Phalcon\Mvc\Model;

class Pages extends Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var integer
     */
    public $standalone;

    /**
     *
     * @var string
     */
    public $url;

    /**
     * Internal counter of the grid width
     * @var int
     */
    public $widthCount = 0;

    /**
     * Start of row
     * @var string
     */
    public $start = '<div class="row">';

    /**
     * End of row
     * @var string
     */
    public $end = '</div>';

    /**
     * 
     * @param Contents $content
     * @return string
     */
    public function newRow($content, $sort = false)
    {
        if ($sort) {
            $this->start = '<div class="row"><div class="sortable">';
            $this->end = '</div><a href="#"><i class="fa fa-arrows-v right"></i></a></div>';
        }
        $string = '';
        if ($this->widthCount + ($content->offset + $content->width) > 12) {
            $this->widthCount = 0;
            $string .= $this->end;
        }
        if ($this->widthCount == 0) {
            $string .= $this->start;
        }
        return $string;
    }

    /**
     * 
     * @param Contents $content
     * @return string
     */
    public function endRow($content)
    {
        $this->widthCount += ($content->offset + $content->width);
        if ($this->widthCount >= 12) {
            $this->widthCount = 0;
            return $this->end;
        }
    }

    /**
     * Ensure consistancy
     */
    public function beforeSave()
    {
        $this->url = strtolower($this->url);
        if ($this->url[0] == '/') {
            $this->url = substr($this->url, 1);
        }
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->keepSnapshots(true);
        $this->addBehavior(new Blameable());
        $this->useDynamicUpdate(true);
        $this->hasMany('id', 'models\Contents', 'page_id', ['alias' => 'Contents']);
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return [
            'id' => 'id',
            'name' => 'name',
            'title' => 'title',
            'standalone' => 'standalone',
            'url' => 'url'
        ];
    }

    public function setWidthCount($value)
    {
        $this->widthCount = $value;
    }

}
