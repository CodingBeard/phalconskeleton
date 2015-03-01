<?php

/**
 * Pages
 *
  CREATE TABLE `contents` (
  `id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `width` TINYINT NULL DEFAULT NULL,
  `offset` TINYINT NULL DEFAULT NULL,
  `content` MEDIUMTEXT NULL DEFAULT NULL,
  `page_id` INT NULL DEFAULT NULL,
  `parent_id` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
  );
  ALTER TABLE `contents` ADD FOREIGN KEY (page_id) REFERENCES `pages` (`id`);
  ALTER TABLE `contents` ADD FOREIGN KEY (parent_id) REFERENCES `contents` (`id`);
 * 
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
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
    
    public function beforeDelete()
    {
        if ($this->children->count()) {
            $this->children->delete();
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
        $this->hasMany('id', 'Contents', 'parent_id', array('alias' => 'Children'));
        $this->belongsTo('parent_id', 'Contents', 'id', array('alias' => 'Parent'));
        $this->belongsTo('page_id', 'Pages', 'id', array('alias' => 'Pages'));
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'ordering' => 'ordering',
            'width' => 'width',
            'offset' => 'offset',
            'content' => 'content',
            'page_id' => 'page_id',
            'parent_id' => 'parent_id'
        );
    }

}
