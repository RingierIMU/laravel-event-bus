<?php

namespace Ringierimu\EventBus;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Ringierimu\EventBus\Exceptions\RequestException;
use Illuminate\Http\Client\RequestException as IlluminateRequestException;

class Client
{
    /**
     * Create a new instance of Event Bus Client.
     *
     * @param  array  $ventureConfig
     */
    public function __construct(
        protected array $ventureConfig
    ) {
    }

    /**
     * Send an Event to the Bus.
     *
     * @param  Event  $event
     * @return void
     *
     * @throws RequestException
     */
    public function send(Event $event): void
    {
        $eventType = $event->getEventType();
        $params = $event->toEventBus($this->ventureConfig);

        if (
            !Arr::get($this->ventureConfig, 'enabled', true) ||
            in_array($eventType, Arr::get($this->ventureConfig, 'dont_report', []))
        ) {
            logger()->debug("$eventType service bus notification [disabled]", [
                'event' => $eventType,
                'params' => $params,
                'tags' => ['service-bus'],
            ]);

            return;
        }

        $response = $this->request()
                         ->retry(3, 100, function ($exception, $request) {
                             // Handle token expiry.
                             if ($exception instanceof IlluminateRequestException && $request->response->status === 401) {
                                 $request->withHeaders([
                                     'x-api-key' => $this->getToken(true),
                                 ]);
                             }

                             return true;
                         })
                         ->withHeaders([
                             'x-api-key' => $this->getToken(),
                         ])
                         ->post('events', [$params]);

        if ($response->failed()) {
            logger()->error($response->status() . ' code received from event bus', [
                'event' => $eventType,
                'params' => $params,
                'tags' => ['service-bus'],
            ]);

            RequestException::requestFailed($response->toException());
        }

        logger()->debug("$eventType service bus notification", [
            'event' => $eventType,
            'params' => $params,
            'tags' => ['service-bus'],
        ]);
    }

    /**
     * Create a request to the Event Bus.
     *
     * @return PendingRequest
     */
    protected function request(): PendingRequest
    {
        return Http::withHeaders([
            'Accept' => 'application/json',
            'Content-type' => 'application/json',
        ])->withOptions([
            'base_uri' => Arr::get($this->ventureConfig, 'endpoint'),
        ]);
    }

    /**
     * Get API Auth Token for the Event bus server.
     *
     * @param  bool  $regenerate
     * @return string
     */
    protected function getToken(bool $regenerate = false): string
    {
        $key = $this->generateTokenCacheKey();

        if ($regenerate) {
            Cache::forget($key);
        }

        return Cache::rememberForever($key, fn () => $this->generateToken());
    }

    /**
     * Generate a new token for authentication with event bus.
     *
     * @return string
     * @throws RequestException
     */
    protected function generateToken(): string
    {
        $response = $this->request()
                         ->retry(3, 100)
                         ->post('login', Arr::only($this->ventureConfig, [
                             'username',
                             'password',
                             'venture_config_id',
                         ]));

        if ($response->clientError()) {
            RequestException::loginFailed($response->toPsrResponse());
        }

        if ($response->failed()) {
            RequestException::requestFailed($response->toException());
        }

        return $response->json('token');
    }

    /**
     * Token cache key.
     *
     * @return string
     */
    protected function generateTokenCacheKey(): string
    {
        return md5(
            'service-bus-token' .
            Arr::get($this->ventureConfig, 'venture_config_id')
        );
    }
}
