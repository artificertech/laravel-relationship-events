<?php

namespace Artificertech\RelationshipEvents\Concerns;

use Artificertech\RelationshipEvents\BelongsToMany;
use Artificertech\RelationshipEvents\Helpers\AttributesMethods;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait HandlesBelongsToManyEvents.
 *
 * @method \Artificertech\RelationshipEvents\BelongsToMany belongsToMany($related, $table = null, $foreignPivotKey = null, $relatedPivotKey = null, $parentKey = null, $relatedKey = null, $relation = null)
 */
trait HandlesBelongsToManyEvents
{
    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function belongsToManySaving($relation, $callback)
    {
        static::registerRelationshipEvent($relation . 'Saving', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function belongsToManySaved($relation, $callback)
    {
        static::registerRelationshipEvent($relation . 'Saved', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function belongsToManyCreating($relation, $callback)
    {
        static::registerRelationshipEvent($relation . 'Creating', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function belongsToManyCreated($relation, $callback)
    {
        static::registerRelationshipEvent($relation . 'Created', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function belongsToManyAttaching($relation, $callback)
    {
        static::registerRelationshipEvent($relation . 'Attaching', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function belongsToManyAttached($relation, $callback)
    {
        static::registerRelationshipEvent($relation . 'Attached', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function belongsToManyDetaching($relation, $callback)
    {
        static::registerRelationshipEvent($relation . 'Detaching', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function belongsToManyDetached($relation, $callback)
    {
        static::registerRelationshipEvent($relation . 'Detached', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function belongsToManyUpdatingExistingPivot($relation, $callback)
    {
        static::registerRelationshipEvent($relation . 'UpdatingExistingPivot', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function belongsToManyUpdatedExistingPivot($relation, $callback)
    {
        static::registerRelationshipEvent($relation . 'UpdatedExistingPivot', $callback);
    }

    /**
     * Instantiate a new BelongsToMany relationship.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model   $parent
     * @param string                                $table
     * @param string                                $foreignPivotKey
     * @param string                                $relatedPivotKey
     * @param string                                $parentKey
     * @param string                                $relatedKey
     * @param string                                $relationName
     *
     * @return \Artificertech\RelationshipEvents\BelongsToMany
     */
    protected function newBelongsToMany(
        Builder $query,
        Model $parent,
        $table,
        $foreignPivotKey,
        $relatedPivotKey,
        $parentKey,
        $relatedKey,
        $relationName = null
    ) {
        return new BelongsToMany($query, $parent, $table, $foreignPivotKey, $relatedPivotKey, $parentKey, $relatedKey, $relationName);
    }

    /**
     * Fire the given event for the model relationship.
     *
     * @param string $event
     * @param string $relation
     * @param mixed  $ids
     * @param array  $attributes
     * @param bool   $halt
     *
     * @return mixed
     */
    public function fireModelBelongsToManyEvent($event, $relation,  $ids, $attributes = [])
    {
        $parsedIds = AttributesMethods::parseIds($ids);
        $parsedIdsForEvent = AttributesMethods::parseIdsForEvent($parsedIds);
        $parseAttributesForEvent = AttributesMethods::parseAttributesForEvent($ids, $parsedIds, $attributes);

        return $this->fireModelRelationshipEvent($event, $relation, true, $this, $ids, $attributes);
    }
}
