<?php

namespace App\Console\Commands\Helpers;

use RuntimeException;
use Tests\Helpers\CommandCallerFake;

class Command
{
    /**
     * Switch to a fake executor for testing purpose.
     *
     * @return CommandCallerContract
     */
    public static function fake() : CommandCallerContract
    {
        static::setBackend($fake = app(CommandCallerFake::class));

        return $fake;
    }

    /**
     * The Command Executor.
     *
     * @var CommandCallerContract
     */
    private static $CommandCaller;

    /**
     * Get the current backend command.
     *
     * @return CommandCallerContract
     */
    private static function getBackend() : CommandCallerContract
    {
        if (is_null(static::$CommandCaller)) {
            static::$CommandCaller = app(CommandCaller::class); // @codeCoverageIgnore
        }

        return static::$CommandCaller;
    }

    /**
     * Set the current backend command.
     *
     * @param CommandCallerContract $executor
     */
    public static function setBackend(CommandCallerContract $executor) : void
    {
        static::$CommandCaller = $executor;
    }

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param  string  $method
     * @param  array  $args
     * @return mixed
     *
     * @throws \RuntimeException
     */
    public static function __callStatic($method, $args)
    {
        $instance = static::getBackend();

        if (! $instance) {
            throw new RuntimeException('No backend.'); // @codeCoverageIgnore
        }

        return $instance->$method(...$args);
    }
}
