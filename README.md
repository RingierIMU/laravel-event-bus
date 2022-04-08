<h2 align="center">
    Laravel Event Bus
</h2>

<p align="center">
<a href="https://github.com/RingierIMU/laravel-event-bus/actions"><img src="https://github.com/RingierIMU/laravel-event-bus/workflows/Quality/badge.svg" alt="Build Status"></a>
<a href="https://github.styleci.io/repos/427248077?branch=main"><img src="https://github.styleci.io/repos/427248077/shield?branch=main" alt="StyleCI"></a>
<a href="https://packagist.org/packages/ringierimu/event-bus"><img src="https://img.shields.io/packagist/dt/ringierimu/event-bus" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/ringierimu/event-bus"><img src="https://img.shields.io/packagist/v/ringierimu/event-bus" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/ringierimu/event-bus"><img src="https://img.shields.io/packagist/l/ringierimu/event-bus" alt="License"></a>
</p>

## Introduction

Laravel Event Bus provides a simple interface that provides a Laravel event
the ability to be dispatched onto [Ringier Event Bus](https://docs.bus.ritdu.net/).

### Installation

Install the package using composer:

```bash
composer require ringierimu/event-bus
```

Next publish the config file using:

```bash
php artisan vendor:publish --provider=Ringierimu\EventBus\EventBusServiceProvider
```

This will create a config file at `config/event-bus.php`. Feel free to browse the file and update it as required
by your application. 

Update your `.env` file with the following variable substitution the correct values for your application:

```dotenv
RINGIER_SB_VENTURE_CONFIG_ID=123456789
RINGIER_SB_USER=event_bus_user
RINGIER_SB_PASSWORD=event_bus_password
```

You are encouraged to have a further look at the `config/event-bus.php` file to learn more about other available configurations.

### Usage

To make a Laravel Event dispatchable onto the bus you only need to have your event class extend the 
`Ringierimu\EventBus\Contracts\ShouldBroadcastToEventBus` interface. You then need to implement 
the `toEventBus` method on the Event class; this will allow you to configure how the 
event will be sent to the bus as. E.g payload, eventType, action and so on.

```php
namespace App\Events

use App\Models\Listing;
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
     * @param  Listing  $listing
     */
    public function __construct(
        public Listing $listing
    ) {
    }

    /**
     * Get the representation of the event for the EventBus.
     *
     * @param  Event  $event
     * @return Event
     */
    public function toEventBus(Event $event): Event
    {
        return $event
            ->withAction('user', $this->listing->user_id)
            ->withPayload([
                'id' => $this->listing->id,
                'title' => $this->listing->title,
                'description' => $this->listing->description
            ]);
    }
}
```

Finally, just dispatch you event as you would any normal Laravel event. Your event will now be dispatched onto the bus.

```php
namespace App\Http\Controllers;

use App\Events\ListingCreatedEvent;
use App\Http\Requests\StoreListingRequest;
use App\Models\Listing;
use Illuminate\Http\RedirectResponse;

class ListingController extends Controller
{
    /**
     * Store a new Listing.
     * 
     * @return RedirectResponse
     */
    public function store(StoreListingRequest $request): RedirectResponse
    {
        $listing = Listing::create($request->validated());
    
        // Event will automatically be dispatched onto the
        // bus as well.
        ListingCreatedEvent::dispatch($listing);
        
        return back();
    }
}
```

### Customising the Event type

By default, event type is being sent as the name of the event class. However, you can customise the type name by using the `withEventType` method 
of the `Ringierimu\EventBus\Event` class. In your `toEventBus` method do the following:

```php
/**
 * Get the representation of the event for the EventBus.
 *
 * @param  Event  $event
 * @return Event
 */
public function toEventBus(Event $event): Event
{
    return $event
        ->withEventType('UserListingCreatedEvent')
        ->withAction('user', $this->listing->user_id)
        ->withPayload([
            'id' => $this->listing->id,
            'title' => $this->listing->title,
            'description' => $this->listing->description
        ]);
}
```

You may also implement a `broadcastToEventBusAs` method on your Laravel Event class, however note that `withEventType` will
take precedence over `broadcastToEventBusAs`.

```php
namespace App\Events

use App\Models\Listing;
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
     * @param  Listing  $listing
     */
    public function __construct(
        public Listing $listing
    ) {
    }

    /**
     * Get the representation of the event for the EventBus.
     *
     * @param  Event  $event
     * @return Event
     */
    public function toEventBus(Event $event): Event
    {
        return $event
            ->withAction('user', $this->listing->user_id)
            ->withPayload([
                'id' => $this->listing->id,
                'title' => $this->listing->title,
                'description' => $this->listing->description
            ]);
    }
    
    /**
     * Get the event type name being sent to the event bus.
     *
     * @return string
     */
    public function broadcastToEventBusAs(): string
    {
        return 'UserListingCreatedEvent';
    }
}
```

### Customising the queue

By default, all event bus events being sent are processed on queue. Your default queue and connection will be used 
for sending dispatching the jobs, however you can specify a dedicated queue and connection for processing your 
events by adding the following to your `.env`:

```dotenv
RINGIER_SB_QUEUE=eventbus
RINGIER_SB_QUEUE_CONNECTION=redis
```

Alternatively you can specify the queue and connection on a per-Event basis by adding 
the `onQueue` and `onConnection` methods
to your Laravel Event classes.

```php
namespace App\Events

use App\Models\Listing;
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
     * @param  Listing  $listing
     */
    public function __construct(
        public Listing $listing
    ) {
    }

    /**
     * Get the representation of the event for the EventBus.
     *
     * @param  Event  $event
     * @return Event
     */
    public function toEventBus(Event $event): Event
    {
        return $event
            ->withAction('user', $this->listing->user_id)
            ->withPayload([
                'id' => $this->listing->id,
                'title' => $this->listing->title,
                'description' => $this->listing->description
            ]);
    }
    
    /**
     * Specify the queue name on which this event should be processed.
     *
     * @param  Event  $event
     * @return string
     */
    public function onQueue(Event $event): string
    {
        return 'eventbus';
    }
    
    /**
     * Specify the queue connection on which this event should be processed.
     *
     * @param  Event  $event
     * @return string
     */
    public function onConnection(Event $event): string
    {
        return 'redis';
    }
}
```

### Testing

```bash
phpunit
```


