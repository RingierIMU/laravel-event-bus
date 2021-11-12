<?php

namespace Ringierimu\EventBus\Tests\Fixtures\Events;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Ringierimu\EventBus\Contracts\ShouldBroadcastToEventBus;

class ListingCreatedEvent implements ShouldBroadcastToEventBus
{
    use Dispatchable, SerializesModels;


}
