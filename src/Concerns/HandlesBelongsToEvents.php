<?php

namespace Artificertech\RelationshipEvents\Concerns;

use Artificertech\RelationshipEvents\BelongsTo;
use Artificertech\RelationshipEvents\BelongsToWithEvents;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait HasBelongsToEvents.
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
        static::registerRelationshipEvent($relation . 'Associating', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function belongsToAssociated($relation, $callback)
    {
        static::registerRelationshipEvent($relation . 'Associated', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function belongsToDissociating($relation, $callback)
    {
        static::registerRelationshipEvent($relation . 'Dissociating', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function belongsToDissociated($relation, $callback)
    {
        static::registerRelationshipEvent($relation . 'Dissociated', $callback);
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
    public function fireModelBelongsToEvent($event, $relation, $parent)
    {
        return $this->fireModelRelationshipEvent($event, $relation, true, $this, $parent);
    }
}