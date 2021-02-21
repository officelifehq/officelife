<?php

namespace Tests\Unit\Services\Company\Adminland\File;

use Tests\TestCase;
use App\Models\Company\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Services\Company\Adminland\File\UploadFile;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UploadFileTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_uploads_a_file(): void
    {
        Storage::fake('local');
        $michael = $this->createAdministrator();

        $file = UploadedFile::fake()->create('bigfile.csv', 100);

        $createdFileObject = (new UploadFile)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'file' => $file,
        ]);

        $this->assertInstanceOf(
            File::class,
            $createdFileObject
        );

        $this->assertDatabaseHas('files', [
            'id' => $createdFileObject->id,
            'company_id' => $createdFileObject->company_id,
            'filename' => 'bigfile.csv',
            'extension' => 'csv',
            'size_in_kb' => 0,
        ]);

        Storage::disk('local')->assertExists($createdFileObject->hashed_filename);
    }
}
