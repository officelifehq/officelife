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
    protected $signature = 'test:changepermission {employee_id} {permission_level}';

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
    public function handle()
    {
        DB::table('employees')
            ->where('id', $this->argument('employee_id'))
            ->update(['permission_level' => $this->argument('permission_level')]);
    }
}
