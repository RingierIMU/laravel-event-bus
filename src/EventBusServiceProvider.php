<?php

namespace Ringierimu\EventBus;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Ringierimu\EventBus\Contracts\ShouldBroadcastToEventBus;
use Ringierimu\EventBus\Listeners\DispatchBroadcastToEventBusJob;

class EventBusServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/event-bus.php' => config_path('event-bus.php'),
            ]);
        }

        Event::listen(
            ShouldBroadcastToEventBus::class,
            DispatchBroadcastToEventBusJob::class
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->configure();

        $this->app->bind(Client::class, function () {
            $clientConfig = [
                'culture' => config('event-bus.culture'),
                'dont_report' => config('event-bus.dont_report'),
                'enabled' => config('event-bus.enabled'),
                'endpoint' => config('event-bus.credentials.endpoint'),
                'password' => config('event-bus.credentials.password'),
                'username' => config('event-bus.credentials.username'),
                'venture_config_id' => config('event-bus.venture_config_id'),
                'validator_url' => config('event-bus.validator_url'),
                'version' =>config('event-bus.credentials.version'),
                'send_notifications' => config('event-bus.send_notifications'),
            ];

            return new Client($clientConfig);
        });
    }

    /**
     * Set up the configuration for Event Bus.
     *
     * @return void
     */
    protected function configure()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/event-bus.php', 'event-bus'
        );
    }
}
