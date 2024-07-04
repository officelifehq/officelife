<?php

namespace App\Jobs;

use App\Models\Company\Place;
use App\Services\Company\Place\GetGPSCoordinate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchAddressGeocoding implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The place instance.
     */
    public Place $place;

    /**
     * Create a new job instance.
     */
    public function __construct(Place $place)
    {
        $this->place = $place;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        (new GetGPSCoordinate)->execute([
            'place_id' => $this->place->id,
        ]);
    }
}
