<?php

namespace Artificertech\RelationshipEvents;

use Artificertech\RelationshipEvents\Contracts\EventDispatcher;
use Artificertech\RelationshipEvents\Traits\HasEventDispatcher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany as BelongsToManyBase;

/**
 * Class BelongsToMany.
 *
 *
 * @property-read \Artificertech\RelationshipEvents\Concerns\HasBelongsToManyEvents $parent
 */
class BelongsToMany extends BelongsToManyBase implements EventDispatcher
{
    use HasEventDispatcher;

    /**
     * Save a new model and attach it to the parent model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  array  $pivotAttributes
     * @param  bool  $touch
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function save(Model $model, array $pivotAttributes = [], $touch = true)
    {
        if ($this->willDispatchEvents() && $this->parent->fireModelRelationshipEvent('saving', $this->eventRelationship, true, $this->parent, $model, $pivotAttributes) === false) {
            return false;
        }

        $model->save(['touch' => false]);

        $this->attach($model, $pivotAttributes, $touch);

        if (false !== $model && $this->willDispatchEvents()) {
            $this->parent->fireModelRelationshipEvent('saved', $this->eventRelationship, false, $this->parent, $model, $pivotAttributes);
        }

        return $model;
    }

    /**
     * Create a new instance of the related model.
     *
     * @param  array  $attributes
     * @param  array  $joining
     * @param  bool  $touch
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes = [], array $joining = [], $touch = true)
    {
        $instance = $this->related->newInstance($attributes);

        if ($this->willDispatchEvents() && $this->parent->fireModelRelationshipEvent('creating', $this->eventRelationship, true, $this->parent, $instance, $joining) === false) {
            return false;
        }

        // Once we save the related model, we need to attach it to the base model via
        // through intermediate table so we'll use the existing "attach" method to
        // accomplish this which will insert the record and any more attributes.
        $result = $instance->save(['touch' => false]);

        $this->attach($instance, $joining, $touch);

        if (false !== $result && $this->willDispatchEvents()) {
            $this->parent->fireModelRelationshipEvent('created', $this->eventRelationship, false, $this->parent, $instance, $joining);
        }

        return $instance;
    }

    // /**
    //  * Attach a model to the parent.
    //  *
    //  * @param  mixed  $id
    //  * @param  array  $attributes
    //  * @param  bool  $touch
    //  * @return void
    //  */
    // public function attach($id, array $attributes = [], $touch = true)
    // {
    //     if ($this->using) {
    //         $this->attachUsingCustomClass($id, $attributes);
    //     } else {
    //         // Here we will insert the attachment records into the pivot table. Once we have
    //         // inserted the records, we will touch the relationships if necessary and the
    //         // function will return. We can parse the IDs before inserting the records.

    //         $id = $this->parseIds($id);

    //         // If the "attaching" event returns false we'll bail out of the attach and return
    //         // false, indicating that the attach failed. This provides a chance for any
    //         // listeners to cancel attach operations if validations fail or whatever.
    //         if ($this->willDispatchEvents()) {
    //             $eventRecords = $this->parent->fireModelRelationshipEvent('attaching',  $this->eventRelationship, true, $this->parent, $id, $attributes);
    //         }

    //         $this->newPivotStatement()->insert($this->formatAttachRecords(
    //             $id,
    //             $attributes
    //         ));
    //     }

    //     if ($touch) {
    //         $this->touchIfTouching();
    //     }

    //     if ($this->willDispatchEvents()) {
    //         $this->parent->fireModelRelationshipEvent('attached', $this->eventRelationship, false, $this->parent, $eventRecords);
    //     }
    // }

    // /**
    //  * Attach a model to the parent using a custom class.
    //  *
    //  * @param  mixed  $id
    //  * @param  array  $attributes
    //  * @return void
    //  */
    // protected function attachUsingCustomClass($id, array $attributes)
    // {
    //     $records = $this->formatAttachRecords(
    //         $this->parseIds($id),
    //         $attributes
    //     );

    //     foreach ($records as $record) {
    //         $this->newPivot($record, false)->save();
    //     }
    // }

    // /**
    //  * Detach models from the relationship.
    //  *
    //  * @param mixed $ids
    //  * @param bool  $touch
    //  *
    //  * @return int
    //  */
    // public function detach($ids = null, $touch = true)
    // {
    //     // Get detached ids to pass them to event
    //     $ids = $ids ?? $this->parent->{$this->getRelationName()}->pluck($this->relatedKey);

    //     $this->parent->fireModelBelongsToManyEvent('detaching', $this->getRelationName(), $ids);

    //     if ($result = parent::detach($ids, $touch)) {
    //         // If records are detached fire detached event
    //         // Note: detached event will be fired even if one of all records have been detached
    //         $this->parent->fireModelBelongsToManyEvent('detached', $this->getRelationName(), $ids, [], false);
    //     }

    //     return $result;
    // }

    // /**
    //  * Update an existing pivot record on the table.
    //  *
    //  * @param mixed $id
    //  * @param array $attributes
    //  * @param bool  $touch
    //  *
    //  * @return int
    //  */
    // public function updateExistingPivot($id, array $attributes, $touch = true)
    // {
    //     // If the "associating" event returns false we'll bail out of the associate and return
    //     // false, indicating that the associate failed. This provides a chance for any
    //     // listeners to cancel associate operations if validations fail or whatever.
    //     if ($this->willDispatchEvents() && $this->parent->fireModelBelongsToManyEvent('updatingExistingPivot', $this->eventRelationship, $id, $attributes) === false) {
    //         return 0;
    //     }

    //     $result = parent::updateExistingPivot($id, $attributes, $touch);

    //     if ($result && $this->willDispatchEvents()) {
    //         $this->parent->fireModelBelongsToManyEvent('updatedExistingPivot', $this->getRelationName(), $id, $attributes, false);
    //     }

    //     return $result;
    // }
}
