<?php

namespace Artificertech\RelationshipEvents\Concerns;

use Artificertech\RelationshipEvents\MorphOne;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait HasMorphOneEvents.
 *
 */
trait HandlesMorphOneEvents
{
    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphOneSaving($relation, $callback)
    {
        static::registerModelEvent($relation . 'Saving', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphOneSaved($relation, $callback)
    {
        static::registerModelEvent($relation . 'Saved', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphOneCreating($relation, $callback)
    {
        static::registerModelEvent($relation . 'Creating', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphOneCreated($relation, $callback)
    {
        static::registerModelEvent($relation . 'Created', $callback);
    }

    /**
     * Instantiate a new MorphOne relationship.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Database\Eloquent\Model  $parent
     * @param  string  $type
     * @param  string  $id
     * @param  string  $localKey
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    protected function newMorphOne(Builder $query, Model $parent, $type, $id, $localKey)
    {
        return new MorphOne($query, $parent, $type, $id, $localKey);
    }
}
