<?php

namespace Ringierimu\EventBus\Tests\Unit;

use Ringierimu\EventBus\Event;
use Ringierimu\EventBus\Exceptions\InvalidConfigException;
use Ringierimu\EventBus\Tests\TestCase;

class EventTest extends TestCase
{
    public function test_it_can_construct_an_event()
    {
        $event = Event::make('FooBar');

        $this->assertInstanceOf(Event::class, $event);
    }
}
