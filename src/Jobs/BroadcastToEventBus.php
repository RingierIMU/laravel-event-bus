<?php

namespace Ringierimu\EventBus\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Ringierimu\EventBus\Client;
use Ringierimu\EventBus\Event;
use Ringierimu\EventBus\Exceptions\RequestException;

class BroadcastToEventBus implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds after which the job's unique lock will be released.
     *
     * @var int
     */
    public int $uniqueFor = 3600;

    /**
     * Create a new job instance.
     *
     * @param  Event  $event
     * @return void
     */
    public function __construct(
        protected Event $event
    ) {
    }

    /**
     * Execute the job.
     *
     * @param  Client  $eventBusClient
     * @return void
     *
     * @throws RequestException
     */
    public function handle(Client $eventBusClient)
    {
        $eventBusClient->send($this->event);
    }

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId(): string
    {
        return $this->event->id();
    }
}
