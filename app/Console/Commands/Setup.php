<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Application;
use Illuminate\Support\Facades\Schema;
use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Output\OutputInterface;

class Setup extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup
                            {--force : Force the operation to run when in production.}
                            {--skip-storage-link : Skip storage link create.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install or update the application, and run migrations after a new release';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if ($this->confirmToProceed()) {
            // Clear or rebuild all cache
            if (config('cache.default') != 'database' || Schema::hasTable(config('cache.stores.database.table'))) {
                $this->artisan('✓ Resetting application cache', 'cache:clear');
            }

            if ($this->getLaravel()->environment() == 'production') {
                $this->artisan('✓ Clear config cache', 'config:clear');
                $this->artisan('✓ Resetting route cache', 'route:cache');
                $this->artisan('✓ Resetting view cache', 'view:clear');
            } else {
                $this->artisan('✓ Clear config cache', 'config:clear');
                $this->artisan('✓ Clear route cache', 'route:clear');
                $this->artisan('✓ Clear view cache', 'view:clear');
            }

            if ($this->option('skip-storage-link') !== true
                && $this->getLaravel()->environment() != 'testing'
                && ! file_exists(public_path('storage'))) {
                $this->artisan('✓ Symlink the storage folder', 'storage:link');
            }

            $this->artisan('✓ Performing migrations', 'migrate', ['--force']);

            // Cache config
            if ($this->getLaravel()->environment() == 'production'
                && (config('cache.default') != 'database' || Schema::hasTable(config('cache.stores.database.table')))) {
                $this->artisan('✓ Cache configuraton', 'config:cache');
            }

            $this->line('Officelife '.config('officelife.app_version').' is set up, enjoy.');
        }
    }

    /**
     * @codeCoverageIgnore
     * @param mixed $message
     * @param mixed $commandline
     */
    public function exec($message, $commandline)
    {
        $this->info($message);
        $this->line($commandline, null, OutputInterface::VERBOSITY_VERBOSE);
        exec($commandline.' 2>&1', $output);
        foreach ($output as $line) {
            $this->line($line, null, OutputInterface::VERBOSITY_VERY_VERBOSE);
        }
        $this->line('', null, OutputInterface::VERBOSITY_VERBOSE);
    }

    /**
     * @codeCoverageIgnore
     * @param mixed $message
     * @param mixed $commandline
     */
    public function artisan($message, $commandline, array $arguments = [])
    {
        $info = '';
        foreach ($arguments as $key => $value) {
            if (is_string($key)) {
                $info .= ' '.$key.'="'.$value.'"';
            } else {
                $info .= ' '.$value;
            }
        }
        $this->exec($message, Application::formatCommandString($commandline.$info));
    }
}
