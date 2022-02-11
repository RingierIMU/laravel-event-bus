<h2 align="center">
    Laravel Event Bus
</h2>

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

This will create a config file at ```config/event-bus.php```. Feel free to browse the file and update it as required
by your application.

Next add the following section to your ```config/services.php```

```php
'service_bus' => [
    'culture' => env('RINGIER_SB_CULTURE', 'en_GB'),
    'dont_report' => [
        // Add service bus event types here that
        // should not be logged (too much traffic).
        //
        // For example: ListingViewed
     ],
     'enabled' => env('RINGIER_SB_ENABLED', true),
     'endpoint' => env('RINGIER_SB_ENDPOINT', 'https://bus.ritdu.net/v1/'),
     'password' => env('RINGIER_SB_PASSWORD'),
     'username' => env('RINGIER_SB_USER'),
     'venture_config_id' => env('RINGIER_SB_VENTURE_CONFIG_ID'),
     'validator_url' => 'https://validator.bus.520152236921.ritdu.tech/api/schema/validate',
     'version' => env('RINGIER_SB_VERSION', '0.3.0'),
     'send_notifications' => env('RINGIER_SB_SEND_NOTIFICATION', true),
],
```

### Usage

To make a Laravel Event dispatchable onto the bus you only need to have your event class extend the ```Ringierimu\EventBus\Contracts\ShouldBroadcastToEventBus``` interface.
You the need to implement the ```withServiceBusEventAs``` method on the Event class; this will allow you to configure
how the event will be sent to the bus as. E.g payload, eventType, action and so on.

```php
<?php

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
     * @param Listing $listing
     */
    public function __construct(
        public Listing $listing
    ) {
    }

    /**
     * Get the representation of the event for the EventBus.
     *
     * @param Event $event
     * @return Event
     */
    public function withServiceBusEventAs(Event $event): Event
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
<?php

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

### Testing

```bash
phpunit
```


