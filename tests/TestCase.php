<?php

namespace Ringierimu\EventBus\Tests;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Ringierimu\EventBus\EventBusServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * Define environment setup.
     *
     * @param  Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('event-bus.venture', [
            'culture' => 'en_GB',
            'dont_report' => [],
            'enabled' => true,
            'endpoint' => 'https://bus-staging.ritdu.net/v1/',
            'password' => 'password',
            'username' => 'bus-user',
            'venture_config_id' => '21ea5c49-e3de-48ed-90a8-90495030cf4d',
            'version' => '0.3.0',
        ]);
    }

    /**
     * Get package providers.
     *
     * @param  Application  $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [EventBusServiceProvider::class];
    }
}
