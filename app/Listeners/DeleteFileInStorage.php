<?php

namespace App\Listeners;

use Uploadcare\Api;
use App\Events\FileDeleted;
use App\Models\Company\File;
use Uploadcare\Configuration;
use Http\Client\Exception\HttpException;
use Uploadcare\File\File as UploadcareFile;
use App\Exceptions\EnvVariablesNotSetException;
use Uploadcare\Interfaces\File\FileInfoInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DeleteFileInStorage
{
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
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  FileDeleted  $event
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
