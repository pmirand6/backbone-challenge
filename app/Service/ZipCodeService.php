<?php
/**
 * Creator: Pablo Miranda
 * Date: 2022/05/18 19:51
 */

namespace App\Service;

use App\Models\ZipCode;
use App\Repository\ZipCodeRepository;
use App\Repository\ZipCodeRepositoryContract;

class ZipCodeService
{
    /**
     * @var ZipCodeRepository
     */
    private $zipCodeRepository;

    /**
     * ZipCodeService constructor.
     * @param ZipCodeRepositoryContract $zipCodeRepository
     */
    public function __construct(ZipCodeRepositoryContract $zipCodeRepository)
    {
        $this->zipCodeRepository = $zipCodeRepository;
    }

    /**
     * @param string $zipCode
     * @return ZipCode|null
     */
    public function getZipCode(string $zipCode): ?ZipCode
    {
        return $this->zipCodeRepository->findOneBy(['zipCode' => $zipCode]);
    }
}
