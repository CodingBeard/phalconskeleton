<?php

/**
 * Form Data
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

class Formdatas extends Model
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
    public $formentry_id;

    /**
     *
     * @var integer
     */
    public $field_id;

    /**
     *
     * @var string
     */
    public $value;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->keepSnapshots(true);
        $this->addBehavior(new Blameable());
        $this->useDynamicUpdate(true);
        $this->belongsTo("field_id", "models\Formfields", "id", ['alias' => 'Formfields']);
        $this->belongsTo("formentry_id", "models\Formentrys", "id", ['alias' => 'Formentrys']);
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return [
            'id' => 'id',
            'formentry_id' => 'formentry_id',
            'field_id' => 'field_id',
            'value' => 'value'
        ];
    }

}
