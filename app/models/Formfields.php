<?php

/**
 * Form Fields
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

class Formfields extends Model
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
    public $form_id;

    /**
     *
     * @var string
     */
    public $fieldKey;

    /**
     *
     * @var string
     */
    public $fieldName;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->keepSnapshots(true);
        $this->addBehavior(new Blameable());
        $this->useDynamicUpdate(true);
        $this->hasMany("id", "models\Formdatas", "field_id", ['alias' => 'Formdatas']);
        $this->belongsTo("form_id", "models\Looseforms", "id", ['alias' => 'Looseforms']);
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return [
            'id' => 'id',
            'form_id' => 'form_id',
            'fieldKey' => 'fieldKey',
            'fieldName' => 'fieldName'
        ];
    }

}
