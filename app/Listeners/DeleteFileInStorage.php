<?php

namespace App\Listeners;

use App\Events\FileDeleted;
use App\Exceptions\EnvVariablesNotSetException;
use App\Models\Company\File;
use Http\Client\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Uploadcare\Api;
use Uploadcare\Configuration;
use Uploadcare\File\File as UploadcareFile;
use Uploadcare\Interfaces\File\FileInfoInterface;

class DeleteFileInStorage
{
    /**
     * The file instance.
     */
    public File $file;

    /**
     * The file in Uploadcare instance.
     */
    public FileInfoInterface $fileInUploadcare;

    /**
     * The API used to query Uploadcare.
     */
    public Api $api;

    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(FileDeleted $event)
    {
        $this->file = $event->file;
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
