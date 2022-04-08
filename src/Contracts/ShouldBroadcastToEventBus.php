<?php

namespace Ringierimu\EventBus\Contracts;

use Ringierimu\EventBus\Event;

interface ShouldBroadcastToEventBus
{
    /**
     * Get the representation of the event for the event bus.
     *
     * @param  Event  $event
     * @return Event
     */
    public function toEventBus(Event $event): Event;
}
