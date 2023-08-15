<?php

namespace Ringierimu\EventBus\Tests\Fixtures\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Ringierimu\EventBus\Contracts\ShouldBroadcastToEventBus;
use Ringierimu\EventBus\Event;

class ListingCreatedEvent implements ShouldBroadcastToEventBus
{
    use Dispatchable, SerializesModels;

    /**
     * Create an instance of ListingCreated event.
     *
     * @param  array  $listing
     */
    public function __construct(
        public array $listing
    ) {
    }

    /**
     * Get the representation of the event for the event bus.
     *
     * @param  Event  $event
     * @return Event
     */
    public function toEventBus(Event $event): Event
    {
        return $event
            ->withPayload($this->listing);
    }
}
