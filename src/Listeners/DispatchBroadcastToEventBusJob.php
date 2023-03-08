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

        $busEvent = $event->toEventBus(Event::make($eventType));

        $queue = method_exists($event, 'onQueue')
            ? $event->onQueue($busEvent)
            : config('event-bus.queue');

        $connection = method_exists($event, 'onConnection')
            ? $event->onConnection($busEvent)
            : config('event-bus.queue_connection');

        $delay = method_exists($event, 'delay')
            ? $event->delay($busEvent)
            : config('event-bus.delay');

        BroadcastToEventBus::dispatch($busEvent)
            ->onQueue($queue)
            ->onConnection($connection)
            ->delay($delay);
    }
}
