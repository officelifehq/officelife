<?php

namespace Tests\Unit\Controllers\Company;

use Tests\TestCase;

class AuditControllerTest extends TestCase
{
    /** @test */
    public function it_lets_you_see_the_audit_list_only_with_the_right_permissions()
    {
        $route = '/account/audit';
        $this->accessibleBy(config('homas.authorizations.administrator'), $route, 200);
        $this->accessibleBy(config('homas.authorizations.hr'), $route, 401);
        $this->accessibleBy(config('homas.authorizations.user'), $route, 401);
    }
}
