<?php

namespace Ringierimu\EventBus\Tests\Unit;

use Mockery\MockInterface;
use Ringierimu\EventBus\Client;
use Ringierimu\EventBus\Tests\Fixtures\Events\ListingCreatedEvent;
use Ringierimu\EventBus\Tests\TestCase;

class BroadcastToEventBusTest extends TestCase
{
    public function test_it_can_send_an_event_to_bus()
    {
        $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('send')->once();
        });

        ListingCreatedEvent::dispatch([
            'id' => 1,
            'user_id' => 1,
            'title' => 'Listing title',
            'description' => 'Listing description'
        ]);
    }
}
