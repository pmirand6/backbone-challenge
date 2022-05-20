<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Service\ZipCodeService;
use Illuminate\Http\JsonResponse;

class GetZipCodesFileController extends Controller
{
    public function __construct(private ZipCodeService $zipCodeService)
    {
    }

    public function __invoke(string $zipCode)
    {
        $result = $this->zipCodeService->getZipCode($zipCode);

        return response()->json($result);
    }
}
