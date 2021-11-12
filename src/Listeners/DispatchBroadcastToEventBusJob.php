<?php

namespace Ringierimu\EventBus\Listeners;

use Ringierimu\EventBus\Contracts\ShouldBroadcastToEventBus;

class DispatchBroadcastToEventBusJob
{
    /**
     * Handle the event.
     *
     * @param  ShouldBroadcastToEventBus  $event
     * @return void
     */
    public function handle(ShouldBroadcastToEventBus $event)
    {
        // Access the order using $event->order...
    }
}
