<?php

namespace App\Events;

use App\Models\Company\File;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class FileDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public File $file;

    /**
     * Create a new event instance.
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }
}
