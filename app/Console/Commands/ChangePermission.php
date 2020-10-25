<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ChangePermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:changepermission
                            {--employee= : Id of the employee in DB}
                            {--user= : Id of the User in DB}
                            {--permission= : Permission level}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change the permission of the employee';

    /**
     * Create a new command instance.
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
        if ($this->option('employee')) {
            DB::table('employees')
                ->where('id', $this->option('employee'))
                ->update(['permission_level' => $this->option('permission')]);
        } elseif ($this->option('user')) {
            DB::table('employees')
                ->where('user_id', $this->option('user'))
                ->update(['permission_level' => $this->option('permission')]);
        }
    }
}
