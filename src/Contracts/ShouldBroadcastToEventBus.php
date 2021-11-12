<?php

namespace Ringierimu\EventBus\Contracts;

use Ringierimu\EventBus\Event;

interface ShouldBroadcastToEventBus
{
    /**
     * Format the 
     * @param  Event  $event
     * @return Event
     */
    public function withServiceBusEventAs(Event $event): Event;
}
