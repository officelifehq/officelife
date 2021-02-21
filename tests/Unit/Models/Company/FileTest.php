<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Services\Company\Adminland\File\UploadFile;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FileTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $file = File::factory()->create([]);
        $this->assertTrue($file->company()->exists());
    }

    /** @test */
    public function it_gets_the_path(): void
    {
        Storage::fake('local');

        $michael = $this->createAdministrator();
        $file = UploadedFile::fake()->create('bigfile.csv', 100);

        $createdFileObject = (new UploadFile)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'file' => $file,
        ]);

        $this->assertNotFalse(strpos($createdFileObject->path, '/storage/app/'.$createdFileObject->hashed_filename));
    }
}
