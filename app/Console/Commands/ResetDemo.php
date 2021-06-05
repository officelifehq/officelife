<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
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
        if ($this->confirmToProceed()) {
            try {
                Artisan::call('down');

                $this->line('Downlading file...');
                $file = Http::get('https://github.com/officelifehq/demosql/raw/main/officelife.sql');

                $this->line('Running transaction...');
                DB::unprepared($file->body());

                $this->line('Running migration...');
                Artisan::call('migrate', ['--force' => true]);
            } finally {
                Artisan::call('up');
            }

            $this->line('Database cleaned.');
        }
    }
}
