<?php

/**
 * Blameable
 * 
 * Creates an auditing trail for all model CRUD actions models required: Audits, Auditfields
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */
use Phalcon\Mvc\Model\Behavior;
use Phalcon\Mvc\Model\BehaviorInterface;
use Phalcon\Mvc\ModelInterface;

class Blameable extends Behavior implements BehaviorInterface
{

    /**
     * Class constructor.
     *
     * @param array $options
     */
    public function __construct($options = null)
    {
        $this->_options = $options;
    }

    /**
     * {@inheritdoc}
     *
     * @param string                      $eventType
     * @param \Phalcon\Mvc\ModelInterface $model
     */
    public function notify($eventType, \Phalcon\Mvc\ModelInterface $model)
    {
        if ($eventType == 'afterCreate') {
            return $this->auditAfterCreate($model);
        }

        if ($eventType == 'afterUpdate') {
            return $this->auditAfterUpdate($model);
        }

        if ($eventType == 'beforeDelete') {
            return $this->auditBeforeDelete($model);
        }
    }

    /**
     * Creates an Audit isntance based on the current enviroment
     *
     * @param  string                      $type
     * @param  \Phalcon\Mvc\ModelInterface $model
     * @return Audit
     */
    public function createAudit($type, ModelInterface $model)
    {
        $auth = $model->getDI()->get('auth');
        $request = $model->getDI()->getRequest();

        $audit = new \Audits();
        if ($auth) {
            $audit->user_id = $auth->audit_id;
        }
        else {
            $audit->user_id = null;
        }
        $audit->modelName = get_class($model);
        $audit->row_id = $model->readAttribute('id');
        $ip = $request->getClientAddress();
        if ($ip) {
            $audit->ip = $ip;
        }
        else {
            $audit->ip = 'CLI';
        }
        $audit->type = $type;
        $audit->date = date('Y-m-d H:i:s');

        return $audit;
    }

    /**
     * Audits an CREATE operation
     *
     * @param  \Phalcon\Mvc\ModelInterface $model
     * @return boolean
     */
    public function auditAfterCreate(ModelInterface $model)
    {
        //Create a new audit
        $audit = $this->createAudit('C', $model);
        return $audit->save();
    }

    /**
     * Audits an UPDATE operation
     *
     * @param  \Phalcon\Mvc\ModelInterface $model
     * @return boolean
     */
    public function auditAfterUpdate(ModelInterface $model)
    {
        $changedFields = $model->getChangedFields();

        if (is_array($changedFields) && count($changedFields)) {
            $audit = $this->createAudit('U', $model);
            $originalData = $model->getSnapshotData();

            $fields = [];
            foreach ($changedFields as $field) {
                $auditfield = new \Auditfields();
                $auditfield->fieldName = $field;
                $auditfield->oldValue = $originalData[$field];
                $auditfield->newValue = $model->readAttribute($field);
                $fields[] = $auditfield;
            }
            $audit->auditfields = $fields;

            return $audit->save();
        }
        return null;
    }

    /**
     * Audits an DELETE operation
     *
     * @param  \Phalcon\Mvc\ModelInterface $model
     * @return boolean
     */
    public function auditBeforeDelete(ModelInterface $model)
    {
        $audit = $this->createAudit('D', $model);

        foreach ($model->columnMap() as $field) {
            $auditfield = new \Auditfields();
            $auditfield->fieldName = $field;
            $auditfield->oldValue = $model->readAttribute($field);
            $auditfield->newValue = null;
            $fields[] = $auditfield;
        }
        $audit->auditfields = $fields;

        $audit->save();
        
        /**
         * Cascade deletes, potentially very dangerous.
         */
        $manager = $model->getModelsManager();
        foreach ($manager->getHasOneAndHasMany($model) as $relation) {
            $children = $model->getRelated($relation->getOptions()['alias']);
            if ($children->count()) {
                $children->delete();
            }
        }
    }

}
