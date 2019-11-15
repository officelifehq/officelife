<?php

namespace Tests\Unit\Controllers\Company\Adminland;

use Tests\TestCase;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminAuditControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_lets_you_see_the_audit_list_only_with_the_right_permissions(): void
    {
        $route = '/account/audit';
        $employee = factory(Employee::class)->create([]);

        $this->accessibleBy($employee, config('kakene.authorizations.administrator'), $route, 200);
        $this->accessibleBy($employee, config('kakene.authorizations.hr'), $route, 401);
        $this->accessibleBy($employee, config('kakene.authorizations.user'), $route, 401);
    }
}
