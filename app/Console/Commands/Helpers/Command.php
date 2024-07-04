<?php

namespace App\Console\Commands\Helpers;

use Tests\Helpers\CommandCallerFake;

/**
 * @method static void exec(\Illuminate\Console\Command $command, string $message, string $commandline)
 * @method static void artisan(\Illuminate\Console\Command $command, string $message, string $commandline, array $arguments)
 */
class Command
{
    /**
     * Switch to a fake executor for testing purpose.
     */
    public static function fake(): CommandCallerContract
    {
        static::setBackend($fake = app(CommandCallerFake::class));

        return $fake;
    }

    /**
     * The Command Executor.
     *
     * @var CommandCallerContract|null
     */
    private static $commandCaller;

    /**
     * Get the current backend command.
     */
    private static function getBackend(): CommandCallerContract
    {
        if (! static::$commandCaller) {
            static::$commandCaller = app(CommandCaller::class); // @codeCoverageIgnore
        }

        return static::$commandCaller;
    }

    /**
     * Set the current backend command.
     */
    public static function setBackend(CommandCallerContract $executor): void
    {
        static::$commandCaller = $executor;
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

        return $instance->$method(...$args);
    }
}
