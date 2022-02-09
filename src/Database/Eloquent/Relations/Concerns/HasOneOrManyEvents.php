<?php

namespace Artificertech\RelationshipEvents\Database\Eloquent\Relations\Concerns;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait HasOneOrManyMethods.
 *
 *
 * @property-read \Illuminate\Database\Eloquent\Model $related
 */
trait HasOneOrManyEvents
{

    /**
     * Attach a model instance to the parent model.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return \Illuminate\Database\Eloquent\Model|false
     */
    public function save(Model $model)
    {
        // If the "saving" event returns false we'll bail out of the save and return
        // false, indicating that the save failed. This provides a chance for any
        // listeners to cancel save operations if validations fail or whatever.
        if ($this->willDispatchEvents() && $this->parent->fireModelRelationshipEvent('saving', $this->eventRelationship, true, $this->parent, $model) === false) {
            return false;
        }

        $result = parent::save($model);

        if (false !== $result && $this->willDispatchEvents()) {
            $this->parent->fireModelRelationshipEvent('saved', $this->eventRelationship, false, $this->parent, $result);
        }

        return $result;
    }

    /**
     * Create a new instance of the related model.
     *
     * @param array $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes = [])
    {

        return tap($this->related->newInstance($attributes), function ($instance) {
            // If the "creating" event returns false we'll bail out of the create and return
            // false, indicating that the create failed. This provides a chance for any
            // listeners to cancel create operations if validations fail or whatever.
            $eventResult = $this->parent->fireModelRelationshipEvent('creating', $this->eventRelationship, true, $this->parent, $instance);

            if ($this->willDispatchEvents() && $eventResult === false) {
                return false;
            }

            $this->setForeignAttributesForCreate($instance);

            if ($instance->save() && $this->willDispatchEvents()) {
                $this->parent->fireModelRelationshipEvent('created', $this->eventRelationship, false, $this->parent, $instance);
            }
        });
    }
}
