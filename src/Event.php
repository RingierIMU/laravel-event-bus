<?php

namespace Ringierimu\EventBus;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Ringierimu\EventBus\Exceptions\InvalidConfigException;

class Event
{
    /**
     * Create a new instance of Event.
     *
     * @param  string  $ventureReference
     * @param  string  $eventType
     * @param  Carbon  $createdAt
     * @param  string|null  $culture
     * @param  string|null  $actionType
     * @param  string|null  $actionReference
     * @param  string|null  $route
     * @param  array  $payload
     */
    public function __construct(
        protected string $ventureReference,
        protected string $eventType,
        protected Carbon $createdAt,
        protected ?string $culture = null,
        protected ?string $actionType = null,
        protected ?string $actionReference = null,
        protected ?string $route = null,
        protected array $payload = [],
    ) {
    }

    /**
     * Create an Event to be sent to the Event bus.
     *
     * @param  string  $eventType
     * @param  string|null  $culture
     * @param  Carbon|null  $createdAt
     * @return Event
     */
    public static function make(string $eventType, ?string $culture = null, ?Carbon $createdAt = null): Event
    {
        $culture = $culture ?? config('services.service_bus.culture');
        $createdAt = $createdAt ?? Carbon::now();
        $ventureReference = Str::uuid()->toString();

        return new static($ventureReference, $eventType, $createdAt, $culture);
    }

    /**
     * Source reference for the event.If this is not sent a
     * UUID will be generated and sent with the request.
     *
     * @param  string  $ventureReference
     * @return Event
     */
    public function withReference(string $ventureReference): Event
    {
        $this->ventureReference = $ventureReference;

        return $this;
    }

    /**
     * ISO representation of the language and culture active on the
     * system when the event was created. This can be set here
     * for each individual event, or it can be set in config
     * services.service_bus.culture
     *
     * @param  string  $culture
     * @return Event
     */
    public function withCulture(string $culture): Event
    {
        $this->culture = $culture;

        return $this;
    }

    /**
     * The type needs to be one of config('event-bus.action_types') and
     * represents who initiated the event e.g. a user on the site,
     * an administrator, via an api or internally in the system
     * or app or via a data migration. The reference is who
     * created the event where relevant to the type.Use
     * this to track e.g. which user created a
     * listing. or that a user registered
     * from facebook.
     *
     * @param  string  $type
     * @param  string  $reference
     * @return Event
     *
     * @throws InvalidConfigException
     */
    public function withAction(string $type, string $reference): Event
    {
        $allowedTypes = config('event-bus.action_types');

        if (!in_array($type, $allowedTypes)) {
            throw new InvalidConfigException('Action type must be on of the following: ' . print_r($allowedTypes, true));
        }

        $this->actionType = $type;
        $this->actionReference = $reference;

        return $this;
    }

    /**
     * Event recipient routing, optional and defaulted to empty.
     * Can be used in a recipient for example to choose
     * different services because identified as
     * “high_priority” or “testing”, entirely
     * up to the venture how they want to
     * use this to switch on their
     * recipient.
     *
     * @param  string  $route
     * @return Event
     */
    public function withRoute(string $route): Event
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Date time of the event creation on the event source in ISO8601/RFC3339 format.
     *
     * @param  Carbon  $createdAt
     * @return Event
     */
    public function createdAt(Carbon $createdAt): Event
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Event payload.
     *
     * @param  array  $payload
     * @return Event
     */
    public function withPayload(array $payload): Event
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * Get the Event type.
     *
     * @return string
     */
    public function getEventType(): string
    {
        return $this->eventType;
    }

    /**
     * A Unique identifier to represent the Event internally.
     *
     * @return string
     */
    public function id(): string
    {
        return 'event_' . $this->eventType . '_' . $this->ventureReference;
    }

    /**
     * Return the event as an array that can be sent to the service.
     *
     * @return array
     */
    public function toEventBus(): array
    {
        return [
            'events' => [$this->eventType],
            'venture_reference' => $this->ventureReference,
            'venture_config_id' => config('services.service_bus.venture_config_id'),
            'created_at' => $this->createdAt->toISOString(),
            'culture' => $this->culture,
            'action_type' => $this->actionType,
            'action_reference' => $this->actionReference,
            'version' => config('services.service_bus.version'),
            'route' => $this->route,
            'payload' => $this->payload,
        ];
    }
}
