<?php

namespace Tests\Unit\Listeners;

use Tests\TestCase;
use App\Events\FileDeleted;
use App\Models\Company\File;
use App\Jobs\DeleteFileInUploadcare;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteFileInStorageTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_deletes_a_file_in_uploadcare(): void
    {
        Queue::fake();

        $file = File::factory()->create();

        FileDeleted::dispatch($file);

        Queue::assertPushed(DeleteFileInUploadcare::class, function ($job) use ($file) {
            return $job->file->id == $file->id;
        });
    }
}
