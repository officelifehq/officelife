<?php

namespace Tests\Unit\Services\Company\Adminland\Company;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Services\Company\Adminland\Company\StoreCSV;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StoreCSVTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_stores_a_csv(): void
    {
        Storage::fake('csv');

        $file = UploadedFile::fake()->create('bigfile.csv', 100);

        (new StoreCSV)->execute([
            'file' => $file,
        ]);

        Storage::disk('csv')->assertExists($file->hashName());
    }
}
