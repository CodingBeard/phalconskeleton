<?php

/**
 * Auditfields
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace models;

use Phalcon\Mvc\Model;

class Auditfields extends Model
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
    public $audit_id;

    /**
     *
     * @var string
     */
    public $fieldName;

    /**
     *
     * @var string
     */
    public $oldValue;

    /**
     *
     * @var string
     */
    public $newValue;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo("audit_id", "models\Audits", "id", ['alias' => 'Audits']);
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return [
            'id' => 'id',
            'audit_id' => 'audit_id',
            'fieldName' => 'fieldName',
            'oldValue' => 'oldValue',
            'newValue' => 'newValue'
        ];
    }

}
