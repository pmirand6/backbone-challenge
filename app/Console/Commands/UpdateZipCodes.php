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
        $this->comment('Updating zip codes...');
        $this->info('Attempting to download zip codes...');
        $result = $zipCodeService->downloadFileZipCodes();
        if ($result) {
            $this->info('Zip codes downloaded');
            $this->info('Encoding to utf-8...');
            $encode = $zipCodeService->extractAndEncode();
            $this->info('Encoding done!');
            if ($encode) {
                $this->info('Event Dispatched!');
                UpsertZipCodesFromCommand::dispatch();
                $this->info('The command was successful!');
                return 1;
            } else {
                $this->error('Fail to encode!');
                return 0;
            }
        }

        $this->error('The command was unsuccessful!');
        return 0;
    }

}
