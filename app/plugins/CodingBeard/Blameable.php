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

namespace CodingBeard;

use models\Auditfields;
use models\Audits;
use models\Users;
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
     * @param string $eventType
     * @param ModelInterface $model
     */
    public function notify($eventType, ModelInterface $model)
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
     * @param  string $type
     * @param  ModelInterface $model
     * @return Audits
     */
    public function createAudit($type, ModelInterface $model)
    {
        $auth = $model->getDI()->get('auth');
        $request = $model->getDI()->getRequest();

        $audit = new Audits();
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
     * @param  ModelInterface $model
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
     * @param  ModelInterface $model
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
                $auditfield = new Auditfields();
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
     * @param  ModelInterface $model
     * @return boolean
     */
    public function auditBeforeDelete(ModelInterface $model)
    {
        $audit = $this->createAudit('D', $model);

        foreach ($model->columnMap() as $field) {
            $auditfield = new Auditfields();
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

    /**
     * Catcher for the Blameable functions all models will inherit
     * @param ModelInterface $model
     * @param $method
     * @param null $arguments
     * @return bool|Users|string
     */
    public function missingMethod(ModelInterface $model, $method, $arguments = null)
    {
        switch ($method) {
            case "_createdAt":
                return $this->getCreatedAt($model);
            case "_modifiedAt":
                return $this->getModifiedAt($model);
            case "_creator":
                return $this->getCreator($model);
            case "_modifier":
                return $this->getModifier($model);
            case "_history":
                return $this->getHistory($model);
        }
    }

    /**
     * Get creation date
     * @param ModelInterface $model
     * @return bool|string
     */
    public function getCreatedAt(ModelInterface $model)
    {
        $created = Audits::findFirst([
            'modelName = ?0 AND row_id = ?1 AND type = "C"',
            'bind' => [get_class($model), $model->id]
        ]);
        if ($created) {
            return $created->date;
        }
        return false;
    }

    /**
     * Get last modification date
     * @param ModelInterface $model
     * @return bool|string
     */
    public function getModifiedAt(ModelInterface $model)
    {
        $modified = Audits::findFirst([
            'modelName = ?0 AND row_id = ?1 AND type = "U"',
            'bind' => [get_class($model), $model->id],
            'order' => 'date DESC'
        ]);
        if ($modified) {
            return $modified->date;
        }
        return $this->getCreated($model);
    }

    /**
     * Get the user which created the row
     * @param ModelInterface $model
     * @return bool|Users
     */
    public function getCreator(ModelInterface $model)
    {
        $created = Audits::findFirst([
            'modelName = ?0 AND row_id = ?1 AND type = "C"',
            'bind' => [get_class($model), $model->id]
        ]);
        if ($created) {
            $user = $created->users;
            if ($user) {
                return $user;
            }
            return new Users();
        }
        return false;
    }

    /**
     * Get the last user to modify the row
     * @param ModelInterface $model
     * @return bool|Users
     */
    public function getModifier(ModelInterface $model)
    {
        $modified = Audits::findFirst([
            'modelName = ?0 AND row_id = ?1 AND type = "U"',
            'bind' => [get_class($model), $model->id],
            'order' => 'date DESC'
        ]);
        if ($modified) {
            $user = $modified->users;
            if ($user) {
                return $user;
            }
            return new Users();
        }
        return $this->getCreator($model);
    }

    /**
     * Get the history for a model
     * @param ModelInterface $model
     * @return bool|\Phalcon\Mvc\Model\ResultsetInterface
     */
    public function getHistory(ModelInterface $model)
    {
        $audits = Audits::find([
            'modelName = ?0 AND row_id = ?1',
            'bind' => [get_class($model), $model->id],
            'order' => 'date'
        ]);
        if ($audits) {
            return $audits;
        }
        return false;
    }

}
