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
        $eventType = class_basename($event);

        if (method_exists($event, 'broadcastToEventBusAs')) {
            $eventType = $event->broadcastToEventBusAs();
        }

        $busEvent = $event->withServiceBusEventAs(Event::make($eventType));

        $queue = config('event-bus.queue');
        $connection = config('event-bus.queue_connection');

        if (method_exists($event, 'broadcastToEventBusOnQueue')) {
            $queue = $event->broadcastToEventBusOnQueue($busEvent);
        }

        if (method_exists($event, 'broadcastToEventBusOnQueueConnection')) {
            $connection = $event->broadcastToEventBusOnQueueConnection($busEvent);
        }

        BroadcastToEventBus::dispatch($busEvent)
                           ->onQueue($queue)
                           ->onConnection($connection);
    }
}
