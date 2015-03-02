<?php

/**
 * Pages
 *
  CREATE TABLE `contents` (
  `id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `ordering` INT NULL DEFAULT NULL,
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

    /**
     * Save and execute the contents, returning the result
     * @return string
     */
    public function getContent()
    {
        $di = $this->getDI();
        if ($di->get('config')->pagecontents->allowVolt) {
            $file = $di->get('config')->pagecontents->voldDir . "content-{$this->id}.volt";
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
