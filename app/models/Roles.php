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

namespace models;

use CodingBeard\Blameable;
use Phalcon\Mvc\Model;

class Roles extends Model
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
        $this->addBehavior(new Blameable());
        $this->useDynamicUpdate(true);
        $this->hasManyToMany(
            "id", "models\Userroles", "role_id", "user_id", "models\Users", "id", ['alias' => 'Users']
        );
        $this->hasMany("id", "models\Userroles", "role_id", ['alias' => 'Userroles']);
        $this->hasManyToMany(
            "id", "models\Permissionroles", "role_id", "permission_id", "models\Permissions", "id", ['alias' => 'Permissions']
        );
        $this->hasMany("id", "models\Permissionroles", "role_id", ['alias' => 'Permissionroles']);
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
