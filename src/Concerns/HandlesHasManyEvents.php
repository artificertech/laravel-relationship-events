<?php

namespace Artificertech\RelationshipEvents\Concerns;

use Artificertech\RelationshipEvents\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait HasHasManyEvents.
 *
 */
trait HandlesHasManyEvents
{
    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function hasManySaving($relation, $callback)
    {
        static::registerRelationshipEvent($relation . 'Saving', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function hasManySaved($relation, $callback)
    {
        static::registerRelationshipEvent($relation . 'Saved', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function hasManyCreating($relation, $callback)
    {
        static::registerRelationshipEvent($relation . 'Creating', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function hasManyCreated($relation, $callback)
    {
        static::registerRelationshipEvent($relation . 'Created', $callback);
    }

    /**
     * Instantiate a new HasMany relationship.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model   $child
     * @param string                                $foreignKey
     * @param string                                $ownerKey
     * @param string                                $relation
     *
     * @return \Artificertech\RelationshipEvent\HasMany
     */
    protected function newHasMany(Builder $query, Model $child, $foreignKey, $ownerKey)
    {
        return new HasMany($query, $child, $foreignKey, $ownerKey);
    }

    /**
     * Fire the given event for the model relationship.
     *
     * @param string                                         $event
     * @param string                                         $relation
     * @param \Illuminate\Database\Eloquent\Model|int|string $parent
     * @param bool                                           $halt
     *
     * @return bool
     */
    public function fireModelHasManyEvent($event, $relation, $parent)
    {
        return $this->fireModelRelationshipEvent($event, $relation, true, $this, $parent);
    }
}
