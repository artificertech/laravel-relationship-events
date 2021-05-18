<?php

namespace Artificertech\RelationshipEvents\Concerns;

use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;

/**
 * Trait HasRelationshipObservables.
 *
 *
 * @mixin \Illuminate\Database\Eloquent\Concerns\HasEvents
 */
trait HasRelationshipEvents
{
    use HandlesBelongsToEvents;
    use HandlesHasManyEvents;
    use HandlesHasOneEvents;
    use HandlesMorphToEvents;
    use HandlesMorphOneEvents;
    use HandlesMorphManyEvents;

    /**
     * @var array
     */
    protected static $relationshipObservables = [];

    /**
     * Initialize relationship observables.
     *
     * @return void
     */
    public static function bootHasRelationshipObservables()
    {
        $methods = collect(
            class_uses(static::class)
        )->filter(function ($trait) {
            return Str::startsWith($trait, 'Artificertech\RelationshipEvents\Concerns');
        })->flatMap(function ($trait) {
            $trait = new ReflectionClass($trait);
            $methods = $trait->getMethods(ReflectionMethod::IS_PUBLIC);

            return collect($methods)->filter(function (ReflectionMethod $method) {
                return $method->isStatic();
            })->map(function ($method) {
                return $method->name;
            });
        })->toArray();

        static::mergeRelationshipObservables($methods);
    }

    /**
     * Merge relationship observables.
     *
     * @param array $relationshipObservables
     *
     * @return void
     */
    public static function mergeRelationshipObservables(array $relationshipObservables)
    {
        static::$relationshipObservables = array_merge(static::$relationshipObservables, $relationshipObservables);
    }

    /**
     * Get the observable event names.
     *
     * @return array
     */
    public function getObservableEvents()
    {
        return array_merge(
            parent::getObservableEvents(),
            static::getRelationshipObservables(),
        );
    }

    /**
     * Get relationship observables.
     *
     * @return array
     */
    public static function getRelationshipObservables(): array
    {
        return static::$relationshipObservables;
    }

    /**
     * Fire a custom model event for the given event.
     *
     * @param string $event
     * @param string $method
     * @param array  $params
     *
     * @return mixed|null
     */
    protected function fireCustomModelRelationshipEvent($event, $method, ...$params)
    {
        if (!isset($this->dispatchesEvents[$event])) {
            return;
        }

        $result = static::$dispatcher->$method(new $this->dispatchesEvents[$event]($this, $params));

        if (!is_null($result)) {
            return $result;
        }
    }

    /**
     * Register a model event with the dispatcher.
     *
     * @param string          $event
     * @param \Closure|string $callback
     */
    protected static function registerRelationshipEvent($event, $callback)
    {
        if (isset(static::$dispatcher)) {
            $name = static::class;

            static::$dispatcher->listen("eloquent.{$event}: {$name}", $callback);
        }
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
    public function fireModelRelationshipEvent($event, $relation, $halt = true, ...$params)
    {
        if (!isset(static::$dispatcher)) {
            return true;
        }

        $event =  $this->getEventName($event, $relation);

        // First, we will get the proper method to call on the event dispatcher, and then we
        // will attempt to fire a custom, object based event for the given event. If that
        // returns a result we can return that result, or we'll call the string events.
        $method = $halt ? 'until' : 'dispatch';

        $result = $this->filterModelEventResults(
            $this->fireCustomModelRelationshipEvent($event, $method, $params)
        );

        if (false === $result) {
            return false;
        }

        $result = !empty($result) ? $result : static::$dispatcher->{$method}(
            "eloquent.{$event}: " . static::class,
            $params
        );
    }

    protected function getEventName($event, $relation)
    {
        return $relation . ucfirst($event);
    }
}
