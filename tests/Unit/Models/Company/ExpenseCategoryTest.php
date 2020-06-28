<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\ExpenseCategory;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExpenseCategoryTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $category = factory(ExpenseCategory::class)->create([]);
        $this->assertTrue($category->company()->exists());
    }
}
