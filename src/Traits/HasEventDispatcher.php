<?php

namespace Artificertech\RelationshipEvents\Traits;

use Illuminate\Contracts\Events\Dispatcher;

/**
 * Trait HasEventDispatcher.
 */
trait HasEventDispatcher
{
    /**
     * The relationship name that will be used for events. Set to null to turn off relationship events
     * 
     * @var string|null
     */
    protected $eventRelationship = null;

    /**
     * The event dispatcher instance.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected static $dispatcher;

    /**
     * Get the event dispatcher instance.
     *
     * @return \Illuminate\Contracts\Events\Dispatcher
     */
    public static function getEventDispatcher()
    {
        return static::$dispatcher;
    }

    /**
     * Set the event dispatcher instance.
     *
     * @param \Illuminate\Contracts\Events\Dispatcher $dispatcher
     */
    public static function setEventDispatcher(Dispatcher $dispatcher)
    {
        static::$dispatcher = $dispatcher;
    }

    /**
     * Unset the event dispatcher for models.
     */
    public static function unsetEventDispatcher()
    {
        static::$dispatcher = null;
    }

    /**
     * Turn on events for this relationship
     * 
     * @param string|null $eventRelationship the relationship name that will be used with events
     */
    public function withEvents(string $eventRelationship = null)
    {
        // If no event relationship was given, we will use this debug backtrace to extract
        // the calling method's name and use that as the relationship name as most
        // of the time this will be what we desire to use for the relationships.
        if (is_null($eventRelationship)) {
            $eventRelationship = $this->guessEventRelationship();
        }

        $this->eventRelationship = $eventRelationship;

        return $this;
    }

    /**
     * Turn off events for this relationship
     */
    public function withoutEvents()
    {
        $this->eventRelationship = null;

        return $this;
    }

    /**
     * Guess the "belongs to" relationship name.
     *
     * @return string
     */
    protected function guessEventRelationship()
    {
        [$one, $two, $caller] = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);

        return $caller['function'];
    }

    public function willDispatchEvents(): bool
    {
        return !is_null($this->eventRelationship);
    }
}
