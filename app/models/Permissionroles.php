<?php

/**
 * Permissionroles
 *
CREATE TABLE `permissionroles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permission_id` (`permission_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `permissionroles_ibfk_1` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`),
  CONSTRAINT `permissionroles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB
 * 
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */
 
class Permissionroles extends \Phalcon\Mvc\Model
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
    public $permission_id;
     
    /**
     *
     * @var integer
     */
    public $role_id;
     
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->keepSnapshots(true);
		$this->addBehavior(new \Blameable());
        $this->useDynamicUpdate(true);
		$this->belongsTo("role_id", "Roles", "id", ['alias' => 'Roles']);
		$this->belongsTo("permission_id", "Permissions", "id", ['alias' => 'Permissions']);

    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return [
            'id' => 'id', 
            'permission_id' => 'permission_id', 
            'role_id' => 'role_id'
        ];
    }

}
