<?php
/**
 * Creator: Pablo Miranda
 * Date: 2022/05/18 19:52
 */

namespace App\Repository;

interface ZipCodeRepositoryContract
{

    /**
     * @param string $zipCode
     * @return mixed
     */
    public function findByZipCode(string $zipCode): array;

    public function upsertZipCodes(array $data);
}

