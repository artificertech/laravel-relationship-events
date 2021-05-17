<?php

namespace Artificertech\RelationshipEvents\Contracts;

use Illuminate\Contracts\Events\Dispatcher;

interface EventDispatcher
{
    /**
     * Get the event dispatcher instance.
     *
     * @return \Illuminate\Contracts\Events\Dispatcher
     */
    public static function getEventDispatcher();

    /**
     * Set the event dispatcher instance.
     *
     * @param \Illuminate\Contracts\Events\Dispatcher $dispatcher
     */
    public static function setEventDispatcher(Dispatcher $dispatcher);

    /**
     * Unset the event dispatcher for models.
     */
    public static function unsetEventDispatcher();

    /**
     * Turn on events for this relationship
     */
    public function withEvents(string $relationship = null);

    /**
     * Turn off events for this relationship
     */
    public function withoutEvents();


    /**
     * return whether or not this relationship will dispatch events
     */
    public function willDispatchEvents(): bool;
}
