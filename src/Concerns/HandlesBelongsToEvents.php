<?php

namespace Artificertech\RelationshipEvents\Concerns;

use Artificertech\RelationshipEvents\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait HandlesBelongsToEvents.
 *
 */
trait HandlesBelongsToEvents
{
    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function belongsToAssociating($relation, $callback)
    {
        static::registerModelEvent($relation . 'Associating', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function belongsToAssociated($relation, $callback)
    {
        static::registerModelEvent($relation . 'Associated', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function belongsToDissociating($relation, $callback)
    {
        static::registerModelEvent($relation . 'Dissociating', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function belongsToDissociated($relation, $callback)
    {
        static::registerModelEvent($relation . 'Dissociated', $callback);
    }

    /**
     * Instantiate a new BelongsTo relationship.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model   $child
     * @param string                                $foreignKey
     * @param string                                $ownerKey
     * @param string                                $relation
     *
     * @return \Artificertech\RelationshipEvent\BelongsTo
     */
    protected function newBelongsTo(Builder $query, Model $child, $foreignKey, $ownerKey, $relation)
    {
        return new BelongsTo($query, $child, $foreignKey, $ownerKey, $relation);
    }
}
