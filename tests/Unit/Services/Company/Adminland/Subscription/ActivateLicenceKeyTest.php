<?php

namespace Tests\Unit\Services\Company\Account\Subscription;

use Exception;
use Tests\TestCase;
use App\Models\Company\Company;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Subscription\ActivateLicenceKey;

class ActivateLicenceKeyTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_activates_a_licence_key()
    {
        config(['monica.licence_key_encryption_key' => '123']);
        Http::fake();

        $key = 'W3siZnJlcXVlbmN5IjoiYW5udWFsIiwicHVyY2hhc2VyX2VtYWlsIjoiYWRtaW5AYWRtaW4uY29tIiwibmV4dF9jaGVja19hdCI6IjIwMjMtMDMtMjVUMDA6MDA6MDAuMDAwMDAwWiIsInF1YW50aXR5IjoxMH1d';
        $company = Company::factory()->create([]);

        $request = [
            'company_id' => $company->id,
            'licence_key' => $key,
        ];

        (new ActivateLicenceKey)->execute($request);

        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'licence_key' => 'W3siZnJlcXVlbmN5IjoiYW5udWFsIiwicHVyY2hhc2VyX2VtYWlsIjoiYWRtaW5AYWRtaW4uY29tIiwibmV4dF9jaGVja19hdCI6IjIwMjMtMDMtMjVUMDA6MDA6MDAuMDAwMDAwWiIsInF1YW50aXR5IjoxMH1d',
            'valid_until_at' => '2023-03-25 00:00:00',
            'purchaser_email' => 'admin@admin.com',
            'frequency' => 'annual',
            'quantity' => 10,
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given()
    {
        $request = [];

        $this->expectException(ValidationException::class);
        (new ActivateLicenceKey)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_licence_key_does_not_exist()
    {
        $this->expectException(Exception::class);

        Http::fake(function ($request) {
            return Http::response('', 404);
        });

        $company = Company::factory()->create([]);

        $request = [
            'company_id' => $company->id,
            'licence_key' => '',
        ];

        (new ActivateLicenceKey)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_licence_key_is_not_valid_anymore()
    {
        $this->expectException(Exception::class);

        Http::fake(function ($request) {
            return Http::response('', 900);
        });

        $key = 'W3siZnJlcXVlbmN5IjoiYW5udWFsIiwicHVyY2hhc2VyX2VtYWlsIjoiYWRtaW5AYWRtaW4uY29tIiwibmV4dF9jaGVja19hdCI6IjIwMjMtMDMtMjRUMDA6MDA6MDAuMDAwMDAwWiJ9XQ==';
        $company = Company::factory()->create([]);

        $request = [
            'company_id' => $company->id,
            'licence_key' => $key,
        ];

        (new ActivateLicenceKey)->execute($request);
    }
}
