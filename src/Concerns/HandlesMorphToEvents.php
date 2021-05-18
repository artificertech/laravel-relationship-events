<?php

namespace Artificertech\RelationshipEvents\Concerns;

use Artificertech\RelationshipEvents\MorphTo;
use Artificertech\RelationshipEvents\MorphToWithEvents;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait HandlesMorphToEvents.
 *
 */
trait HandlesMorphToEvents
{
    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphToAssociating($relation, $callback)
    {
        static::registerRelationshipEvent($relation . 'Associating', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphToAssociated($relation, $callback)
    {
        static::registerRelationshipEvent($relation . 'Associated', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphToDissociating($relation, $callback)
    {
        static::registerRelationshipEvent($relation . 'Dissociating', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphToDissociated($relation, $callback)
    {
        static::registerRelationshipEvent($relation . 'Dissociated', $callback);
    }

    /**
     * Instantiate a new MorphTo relationship.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Database\Eloquent\Model  $parent
     * @param  string  $foreignKey
     * @param  string  $ownerKey
     * @param  string  $type
     * @param  string  $relation
     * @return \Artificertech\RelationshipEvent\MorphTo
     */
    protected function newMorphTo(Builder $query, Model $parent, $foreignKey, $ownerKey, $type, $relation)
    {
        return new MorphTo($query, $parent, $foreignKey, $ownerKey, $type, $relation);
    }

    /**
     * Fire the given event for the model relationship.
     *
     * @param string                                         $event
     * @param string                                         $relation
     * @param \Illuminate\Database\Eloquent\Model|int|string $parent
     *
     * @return bool
     */
    public function fireModelMorphToEvent($event, $relation, $parent)
    {
        return $this->fireModelRelationshipEvent($event, $relation, true, $this, $parent);
    }
}
