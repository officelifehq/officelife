<?php

namespace Tests\Unit\Services\Company\Adminland\Company;

use App\Models\Company\Company;
use App\Services\Company\Adminland\Company\UpdateCompanySlug;
use Tests\TestCase;

class UpdateCompanySlugTest extends TestCase
{
    /** @test */
    public function it_updates_a_slug(): void
    {
        $company = Company::factory()->create([
            'name' => 'Dunder Mifflin',
        ]);

        $request = [
            'company_id' => $company->id,
        ];

        $company = (new UpdateCompanySlug)->execute($request);

        $this->assertInstanceOf(
            Company::class,
            $company
        );

        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'slug' => 'dunder-mifflin',
        ]);
    }

    /** @test */
    public function it_updates_a_slug_that_is_unique(): void
    {
        $company = Company::factory()->create([
            'name' => 'Dunder Mifflin',
        ]);
        Company::factory()->create([
            'slug' => 'dunder-mifflin',
        ]);

        $request = [
            'company_id' => $company->id,
        ];

        $company = (new UpdateCompanySlug)->execute($request);

        $this->assertInstanceOf(
            Company::class,
            $company
        );

        $this->assertEquals(
            'dunder-mifflin-',
            substr($company->slug, 0, 15)
        );
    }
}
