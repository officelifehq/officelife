<?php

namespace App\Console\Commands\Tests;

use App\Models\User\User;
use Illuminate\Console\Command;
use App\Services\User\CreateAccount;

class SetupFrontEndTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:frontendtesting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the test environment exclusively for front-end testing with Cypress.';

    /**
     * Create a new command.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        exec('php artisan migrate:fresh && php artisan db:seed');

        $data = [
            'email' => 'admin@admin.com',
            'password' => 'admin',
        ];

        $user = (new CreateAccount)->execute($data);

        User::where('id', $user->id)->update([
            'email_verified_at' => now(),
        ]);
    }
}
