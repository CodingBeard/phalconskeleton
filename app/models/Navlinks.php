<?php

/**
 * Navlinks
 * 
  CREATE TABLE `navlinks` (
  `id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `navbar_id` INT NULL DEFAULT NULL,
  `level` TINYINT NULL DEFAULT NULL,
  `label` VARCHAR(255) NULL DEFAULT NULL,
  `link` VARCHAR(255) NULL DEFAULT NULL,
  `parent_id` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
  );
  ALTER TABLE `navlinks` ADD FOREIGN KEY (navbar_id) REFERENCES `navbars` (`id`);
  ALTER TABLE `navlinks` ADD FOREIGN KEY (parent_id) REFERENCES `navlinks` (`id`);
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
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
     * Cleanup before delete
     */
    public function beforeDelete()
    {
        if ($this->children) {
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
        $this->hasMany('id', 'Navlinks', 'parent_id', array('alias' => 'Children'));
        $this->belongsTo('permission_id', 'Permissions', 'id', array('alias' => 'Permissions'));
        $this->belongsTo('navbar_id', 'Navbars', 'id', array('alias' => 'Navbars'));
        $this->belongsTo('parent_id', 'Navlinks', 'id', array('alias' => 'Parent'));
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'navbar_id' => 'navbar_id',
            'level' => 'level',
            'label' => 'label',
            'link' => 'link',
            'parent_id' => 'parent_id',
        );
    }

}
