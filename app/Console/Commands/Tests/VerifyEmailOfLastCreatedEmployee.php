<?php

namespace App\Console\Commands\Tests;

use App\Models\User\User;
use Illuminate\Console\Command;

class VerifyEmailOfLastCreatedEmployee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:verify-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify the email of the last created employee during a Cypress test.';

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
        $user = User::latest()->first();

        User::where('id', $user->id)->update([
            'email_verified_at' => now(),
        ]);
    }
}
