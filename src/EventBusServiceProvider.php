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
                __DIR__.'/../config/event-bus.php' => config_path('event-bus.php'),
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

        $this->app->bind(Client::class, fn () => new Client(config('event-bus.venture')));
    }

    /**
     * Set up the configuration for Event Bus.
     *
     * @return void
     */
    protected function configure()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/event-bus.php', 'event-bus'
        );
    }
}
