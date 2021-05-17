<?php

namespace Artificertech\RelationshipEvents\Concerns;

use Artificertech\RelationshipEvents\HasOne;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * Trait HasHasOneEvents.
 *
 */
trait HandlesHasOneEvents
{
    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function hasOneSaving($relation, $callback)
    {
        static::registerRelationshipEvent($relation . 'Saving', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function hasOneSaved($relation, $callback)
    {
        static::registerRelationshipEvent($relation . 'Saved', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function hasOneCreating($relation, $callback)
    {
        static::registerRelationshipEvent($relation . 'Creating', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function hasOneCreated($relation, $callback)
    {
        static::registerRelationshipEvent($relation . 'Created', $callback);
    }

    /**
     * Instantiate a new HasOne relationship.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model   $child
     * @param string                                $foreignKey
     * @param string                                $ownerKey
     * @param string                                $relation
     *
     * @return \Artificertech\RelationshipEvent\HasOne
     */
    protected function newHasOne(Builder $query, Model $child, $foreignKey, $ownerKey)
    {
        return new HasOne($query, $child, $foreignKey, $ownerKey);
    }
}
