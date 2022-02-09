<?php

namespace Artificertech\RelationshipEvents;

use Artificertech\RelationshipEvents\Concerns;

/**
 * Trait HasRelationshipObservables.
 *
 *
 * @mixin \Illuminate\Database\Eloquent\Concerns\HasEvents
 */
trait HasRelationshipEvents
{
    use Concerns\HandlesBelongsToEvents;
    use Concerns\HandlesHasManyEvents;
    use Concerns\HandlesHasOneEvents;
    use Concerns\HandlesMorphToEvents;
    use Concerns\HandlesMorphOneEvents;
    use Concerns\HandlesMorphManyEvents;

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

        $event = $this->getEventName($event, $relation);

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

        return !empty($result) ? $result : static::$dispatcher->{$method}(
            "eloquent.{$event}: " . static::class,
            $params
        );
    }

    protected function getEventName($event, $relation)
    {
        return $relation . ucfirst($event);
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
}
