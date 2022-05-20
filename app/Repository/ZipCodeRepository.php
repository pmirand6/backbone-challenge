<?php
/**
 * Creator: Pablo Miranda
 * Date: 2022/05/18 19:52
 */

namespace App\Repository;

use App\Models\ZipCode;
use Illuminate\Support\Facades\DB;
use MongoDB\Collection;

class ZipCodeRepository implements ZipCodeRepositoryContract
{

    public function __construct(private readonly ZipCode $zipCode)
    {
    }


    public function findByZipCode(string $zipCode): array
    {
        //Must return an array of ZipCode, Eloquent model won't convert to array automatically
        $result = DB::collection('zip_codes')
            ->where('zip_code', $zipCode)
            ->first();

        if(!$result) {
            return [];
        }

        // Due to limitation of exclude fields, we need to manually remove fields
        return array_filter($result, function($key) {
            return !in_array($key, ['_id', 'updated_at', 'created_at']);
        }, ARRAY_FILTER_USE_KEY);

    }

    public function upsertZipCodes(array $data): bool
    {
        if(!empty($data)) {
            foreach ($data as $zipCode) {
                $this->zipCode
                    ->where('zip_code', $zipCode['zip_code'])
                    ->update($zipCode, ['upsert' => true]);
            }

            return true;
        }

        return false;
    }
}
