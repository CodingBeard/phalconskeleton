<?php

/**
 * Pages
 *
  CREATE TABLE `pages` (
  `id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `name` VARCHAR(255) NULL DEFAULT NULL,
  `standalone` TINYINT NULL DEFAULT NULL,
  `url` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
  );
 * 
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */
class Pages extends \Phalcon\Mvc\Model
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
     *
     * @var string
     */
    public $start = '<div class="row"><div class="sortable">';
    
    /**
     *
     * @var string
     */
    public $end = '</div><a href="#"><i class="fa fa-arrows-v right"></i></a></div>';
    
    /**
     * 
     * @param \Contents $content
     * @return string
     */
    public function newRow($content)
    {
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
     * @param \Contents $content
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->keepSnapshots(true);
        $this->addBehavior(new \Blameable());
        $this->useDynamicUpdate(true);
        $this->hasMany('id', 'Contents', 'page_id', array('alias' => 'Contents'));
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'name' => 'name',
            'standalone' => 'standalone',
            'url' => 'url'
        );
    }
    
    public function setWidthCount($value)
    {
        $this->widthCount = $value;
    }

}
