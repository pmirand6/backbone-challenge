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
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param UpsertZipCodesFromCommand $event
     * @return void
     */
    public function handle(UpsertZipCodesFromCommand $event, ZipCodeService $zipCodeService)
    {
        $zipCodeService->upsertZipCodes();
    }
}
