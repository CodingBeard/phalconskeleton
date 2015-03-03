<?php

/**
 * Roles
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
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
