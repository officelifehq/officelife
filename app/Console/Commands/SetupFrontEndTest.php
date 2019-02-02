<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Account\Account\CreateAccount;

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

    private $account;

    /**
     * The Command Executor.
     *
     * @var CommandExecutorInterface
     */
    public $commandExecutor;

    /**
     * Create a new command.
     *
     * @param CommandExecutorInterface
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        exec('php artisan migrate:fresh && php artisan db:seed');
        $data = [
            'email' => 'admin@admin.com',
            'password' => 'admin',
        ];

        $account = (new CreateAccount)->execute($data);
        $account->confirmed = 1;
        $account->save();
    }
}
