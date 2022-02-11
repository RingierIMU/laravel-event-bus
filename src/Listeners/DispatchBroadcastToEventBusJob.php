<?php

namespace Ringierimu\EventBus\Listeners;

use Ringierimu\EventBus\Contracts\ShouldBroadcastToEventBus;
use Ringierimu\EventBus\Event;
use Ringierimu\EventBus\Jobs\BroadcastToEventBus;

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
        $busEvent = $event->withServiceBusEventAs(
            Event::make(class_basename($event))
        );

        BroadcastToEventBus::dispatch($busEvent);
    }
}
