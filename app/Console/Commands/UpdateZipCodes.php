<?php

namespace App\Console\Commands;

use App\Events\UpsertZipCodesFromCommand;
use App\Service\ZipCodeService;
use Illuminate\Console\Command;

class UpdateZipCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-zip-codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ZipCodeService $zipCodeService)
    {
        $result = $zipCodeService->downloadFileZipCodes();

        if ($result) {
            $encode = $zipCodeService->extractAndEncode();

            if($encode) {
                UpsertZipCodesFromCommand::dispatch();
            }
        }

        return 0;
    }

}
