<?php

namespace App\Listeners;

use App\Events\FileDeleted;
use App\Jobs\DeleteFileInUploadcare;

class DeleteFileInStorage
{
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
        DeleteFileInUploadcare::dispatch($event->file);
    }
}
