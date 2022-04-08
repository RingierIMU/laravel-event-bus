<?php

namespace Ringierimu\EventBus\Exceptions;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class RequestException extends Exception
{
    /**
     * Could not authenticate with the Event Bus Server.
     *
     * @param  Throwable  $exception
     *
     * @throws RequestException
     */
    public static function authFailed(Throwable $exception)
    {
        logger()->error('Could not get an auth token from the server', [
            'exception' => $exception,
            'tags' => ['service-bus'],
        ]);

        throw new static('Could not get an auth token from the server: '.$exception->getMessage());
    }

    /**
     * Could not authenticate with the Event Bus Server.
     *
     * @param  ResponseInterface  $response
     *
     * @throws RequestException
     */
    public static function loginFailed(ResponseInterface $response)
    {
        logger()->error('Something went wrong logging in', [
            'response' => [
                'statusCode' => $response->getStatusCode(),
                'body' => (string) $response->getBody(),
            ],
            'tags' => ['service-bus'],
        ]);

        throw new static('Something went wrong logging in');
    }

    /**
     * Request to Event Bus server failed.
     *
     * @param  Throwable  $exception
     *
     * @throws RequestException
     */
    public static function requestFailed(Throwable $exception)
    {
        logger()->error('Something went wrong logging the event', [
            'exception' => $exception,
            'tags' => ['service-bus'],
        ]);

        throw new static('Something went wrong logging the event: '.$exception->getMessage());
    }
}
