<?php

namespace App\Console\Commands\Demo;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\ConfirmableTrait;

class ResetDemo extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:reset
                            {--force : Force the operation to run when in production.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset the demo env';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if (! config('officelife.demo_mode')) {
            $this->line('This command is only available in demo mode.');
            return;
        }

        if ($this->confirmToProceed()) {
            try {
                Artisan::call('down');

                if (Cache::has('databasereset')) {
                    $sql = Cache::get('databasereset');
                } else {
                    $this->line('Downloading file...');
                    $file = Http::get('https://github.com/officelifehq/demosql/raw/main/officelife.sql');
                    $file->throw();

                    $sql = $file->body();
                    Cache::put('databasereset', $sql, 7200);
                }

                $this->line('Running transaction...');
                DB::unprepared($sql);

                $this->line('Running migration...');
                Artisan::call('migrate', ['--force' => true, '--verbose' => true]);
            } finally {
                Artisan::call('up');
            }

            $this->line('Database cleaned.');
        }
    }
}
