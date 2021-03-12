<?php

namespace App\Jobs;

use Uploadcare\Api;
use App\Models\Company\File;
use Illuminate\Bus\Queueable;
use Uploadcare\Configuration;
use Illuminate\Queue\SerializesModels;
use Http\Client\Exception\HttpException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Uploadcare\File\File as UploadcareFile;
use App\Exceptions\EnvVariablesNotSetException;
use Uploadcare\Interfaces\File\FileInfoInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DeleteFileInUploadcare implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The file instance.
     *
     * @var File
     */
    public File $file;

    /**
     * The file in Uploadcare instance.
     *
     * @var FileInfoInterface
     */
    public FileInfoInterface $fileInUploadcare;

    /**
     * The API used to query Uploadcare.
     *
     * @var Api
     */
    public Api $api;

    /**
     * Create a new job instance.
     *
     * @param File $file
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->checkAPIKeyPresence();
        $this->getFileFromUploadcare();
        $this->deleteFile();
    }

    private function checkAPIKeyPresence(): void
    {
        if (is_null(config('officelife.uploadcare_private_key'))) {
            throw new EnvVariablesNotSetException();
        }

        if (is_null(config('officelife.uploadcare_public_key'))) {
            throw new EnvVariablesNotSetException();
        }
    }

    private function getFileFromUploadcare(): void
    {
        $configuration = Configuration::create(config('officelife.uploadcare_public_key'), config('officelife.uploadcare_private_key'));
        $this->api = new Api($configuration);

        try {
            $this->fileInUploadcare = $this->api->file()->fileInfo($this->file->uuid);
        } catch (HttpException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    private function deleteFile(): void
    {
        // if (! $this->fileInUploadcare instanceof UploadcareFile) {
        $this->api->file()->deleteFile($this->fileInUploadcare);
        // } else {
        //$this->fileInUploadcare->delete();
        // }
    }
}
