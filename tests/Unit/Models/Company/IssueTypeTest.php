<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\IssueType;
use App\Models\Company\ProjectIssue;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class IssueTypeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $type = IssueType::factory()->create();
        $this->assertTrue($type->company()->exists());
    }

    /** @test */
    public function it_has_many_issues(): void
    {
        $type = IssueType::factory()->create();
        ProjectIssue::factory()->create([
            'issue_type_id' => $type->id,
        ]);
        $this->assertTrue($type->issues()->exists());
    }
}
