<?php

namespace Artificertech\RelationshipEvents\Concerns;

use Artificertech\RelationshipEvents\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait HandlesMorphManyEvents.
 *
 */
trait HandlesMorphManyEvents
{
    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphManySaving($relation, $callback)
    {
        static::registerModelEvent($relation . 'Saving', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphManySaved($relation, $callback)
    {
        static::registerModelEvent($relation . 'Saved', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphManyCreating($relation, $callback)
    {
        static::registerModelEvent($relation . 'Creating', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphManyCreated($relation, $callback)
    {
        static::registerModelEvent($relation . 'Created', $callback);
    }

    /**
     * Instantiate a new MorphMany relationship.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Database\Eloquent\Model  $parent
     * @param  string  $type
     * @param  string  $id
     * @param  string  $localKey
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    protected function newMorphMany(Builder $query, Model $parent, $type, $id, $localKey)
    {
        return new MorphMany($query, $parent, $type, $id, $localKey);
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
    public function fireModelMorphManyEvent($event, $relation, $parent)
    {
        return $this->fireModelRelationshipEvent($event, $relation, true, $this, $parent);
    }
}
