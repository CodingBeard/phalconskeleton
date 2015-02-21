<?php

/**
 * Roles
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `image_id` int(11) DEFAULT NULL,
  `level` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `image_id` (`image_id`),
  CONSTRAINT `roles_ibfk_1` FOREIGN KEY (`image_id`) REFERENCES `images` (`id`)
) ENGINE=InnoDB
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */

class Roles extends \Phalcon\Mvc\Model
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
    public $description;

    /**
     *
     * @var integer
     */
    public $level;
     
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->keepSnapshots(true);
		$this->addBehavior(new \Blameable());
        $this->useDynamicUpdate(true);
        $this->hasManyToMany(
            "id",
            "Userroles",
            "role_id", "user_id",
            "Users",
            "id",
            ['alias' => 'users']
        );
        $this->hasMany("id", "Userroles", "role_id", ['alias' => 'Userroles']);
        $this->hasManyToMany(
            "id",
            "Permissionroles",
            "role_id", "permission_id",
            "Permissions",
            "id"
        );
        $this->hasMany("id", "Permissionroles", "role_id", ['alias' => 'Permissionroles']);

    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return [
            'id' => 'id', 
            'name' => 'name', 
            'description' => 'description',
            'image_id' => 'image_id', 
            'level' => 'level', 
        ];
    }

}
