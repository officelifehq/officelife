<?php

namespace App\Jobs\Dummy;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Company\Adminland\Position\CreatePosition;

class CreateDummyPosition implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The array instance.
     *
     * @var array
     */
    public array $data;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        (new CreatePosition)->execute([
            'company_id' => $this->data['company_id'],
            'author_id' => $this->data['author_id'],
            'title' => $this->data['title'],
            'is_dummy' => true,
        ]);
    }
}
