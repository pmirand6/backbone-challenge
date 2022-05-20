<?php

namespace App\Listeners;

use App\Events\UpsertZipCodesFromCommand;
use App\Service\ZipCodeService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SaveZipCodes
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(private ZipCodeService $zipCodeService)
    {
        //
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle()
    {
        $this->zipCodeService->upsertZipCodes();
    }
}
