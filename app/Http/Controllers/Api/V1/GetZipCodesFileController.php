<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Service\ZipCodeService;

class GetZipCodesFileController extends Controller
{
    public function __construct(private ZipCodeService $zipCodeService)
    {
    }

    public function __invoke()
    {
        $this->zipCodeService->upsertZipCodes();
    }
}
