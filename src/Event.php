<?php

namespace Ringierimu\EventBus;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Ringierimu\EventBus\Exceptions\InvalidConfigException;

class Event
{
    /**
     * Create a new instance of Event.
     *
     * @param  string  $reference
     * @param  string  $eventType
     * @param  Carbon  $createdAt
     * @param  string  $route
     * @param  array  $payload
     */
    public function __construct(
        protected string $reference,
        protected string $eventType,
        protected Carbon $createdAt,
        protected string $route = '',
        protected array $payload = [],
    ) {
    }

    /**
     * Create an Event to be sent to the Event bus.
     *
     * @param  string  $eventType
     * @param  Carbon|null  $createdAt
     * @return Event
     */
    public static function make(string $eventType, ?Carbon $createdAt = null): Event
    {
        $createdAt = $createdAt ?? now();
        $reference = Str::uuid()->toString();

        return new static($reference, $eventType, $createdAt);
    }

    /**
     * Set the Event type.
     *
     * @param  string  $eventType
     * @return Event
     */
    public function withEventType(string $eventType): Event
    {
        $this->eventType = $eventType;

        return $this;
    }

    /**
     * Source reference for the event.If this is not sent a
     * UUID will be generated and sent with the request.
     *
     * @param  string  $reference
     * @return Event
     */
    public function withReference(string $reference): Event
    {
        $this->reference = $reference;

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
        return 'event_'.$this->eventType.'_'.$this->reference;
    }

    /**
     * Return the event as an array that can be sent to the service.
     *
     * @param  Collection  $config
     * @return array
     */
    public function toEventBus(Collection $config): array
    {
        return [
            'created_at' => $this->createdAt->toISOString(),
            'events' => [$this->eventType],
            'from' => $config->get('node_id'),
            'payload' => $this->payload,
            'reference' => $this->reference,
            'route' => $this->route,
            'version' => $config->get('version'),
        ];
    }
}
