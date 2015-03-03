<?php

/**
 * Permissionroles
 * 
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
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
