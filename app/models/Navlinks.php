<?php

/**
 * Navlinks
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */
class Navlinks extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $navbar_id;

    /**
     *
     * @var int
     */
    public $level;

    /**
     *
     * @var string
     */
    public $label;

    /**
     *
     * @var string
     */
    public $link;

    /**
     *
     * @var integer
     */
    public $parent_id;

    /**
     * Check if link is contained in $url
     * @param string $url
     */
    public function inUrl($url)
    {
        if (stripos($url, $this->link) !== false) {
            return true;
        }
        return false;
    }

    /**
     * Check if link equals $url
     * @param string $url
     */
    public function isUrl($url)
    {
        if (substr($url, 0, 1) == '/') {
            $url = substr($url, 1);
        }
        if (substr($url, -1) == '/') {
            $url = substr($url, 0, -1);
        }
        if (strtolower($url) == strtolower($this->link)) {
            return true;
        }
        return false;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->keepSnapshots(true);
        $this->addBehavior(new \Blameable());
        $this->useDynamicUpdate(true);
        $this->hasMany('id', 'Navlinks', 'parent_id', ['alias' => 'Children']);
        $this->belongsTo('navbar_id', 'Navbars', 'id', ['alias' => 'Navbars']);
        $this->belongsTo('parent_id', 'Navlinks', 'id', ['alias' => 'Parent']);
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return [
            'id' => 'id',
            'navbar_id' => 'navbar_id',
            'level' => 'level',
            'label' => 'label',
            'link' => 'link',
            'parent_id' => 'parent_id',
        ];
    }

}
