<?php

namespace Ringierimu\EventBus\Contracts;

use Ringierimu\EventBus\Event;

interface ShouldBroadcastToEventBus
{
    /**
     * Get the representation of the event for the EventBus.
     * 
     * @param  Event  $event
     * @return Event
     */
    public function withServiceBusEventAs(Event $event): Event;
}
