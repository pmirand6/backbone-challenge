<?php
/**
 * Creator: Pablo Miranda
 * Date: 2022/05/18 19:51
 */

namespace App\Service;

use App\Models\ZipCode;
use App\Repository\ZipCodeRepository;
use App\Repository\ZipCodeRepositoryContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ZipCodeService
{
    const URL_MAIL = 'https://www.correosdemexico.gob.mx/SSLServicios/ConsultaCP/CodigoPostal_Exportar.aspx';

    /**
     * ZipCodeService constructor.
     *
     * @param ZipCodeRepositoryContract $zipCodeRepository
     */
    public function __construct(private readonly ZipCodeRepositoryContract $zipCodeRepository)
    {
    }

    /**
     * @param string $zipCode
     * @return array
     */
    public function getZipCode(string $zipCode): array
    {
        return $this->zipCodeRepository->findByZipCode($zipCode);
    }

    public function downloadFileZipCodes(): bool
    {
        $contents = Http::asForm()
            ->withHeaders([
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
                'Accept-Language' => 'es-AR,es;q=0.9,es-419;q=0.8,en;q=0.7,en-GB;q=0.6',
            ])
            ->post(self::URL_MAIL, [
                '__EVENTTARGET' => '',
                '__EVENTARGUMENT' => '',
                '__LASTFOCUS' => '',
                '__VIEWSTATE' => '/wEPDwUINzcwOTQyOTgPZBYCAgEPZBYCAgEPZBYGAgMPDxYCHgRUZXh0BTfDmmx0aW1hIEFjdHVhbGl6YWNpw7NuIGRlIEluZm9ybWFjacOzbjogTWF5byAxNyBkZSAyMDIyZGQCBw8QDxYGHg1EYXRhVGV4dEZpZWxkBQNFZG8eDkRhdGFWYWx1ZUZpZWxkBQVJZEVkbx4LXyFEYXRhQm91bmRnZBAVISMtLS0tLS0tLS0tIFQgIG8gIGQgIG8gIHMgLS0tLS0tLS0tLQ5BZ3Vhc2NhbGllbnRlcw9CYWphIENhbGlmb3JuaWETQmFqYSBDYWxpZm9ybmlhIFN1cghDYW1wZWNoZRRDb2FodWlsYSBkZSBaYXJhZ296YQZDb2xpbWEHQ2hpYXBhcwlDaGlodWFodWERQ2l1ZGFkIGRlIE3DqXhpY28HRHVyYW5nbwpHdWFuYWp1YXRvCEd1ZXJyZXJvB0hpZGFsZ28HSmFsaXNjbwdNw6l4aWNvFE1pY2hvYWPDoW4gZGUgT2NhbXBvB01vcmVsb3MHTmF5YXJpdAtOdWV2byBMZcOzbgZPYXhhY2EGUHVlYmxhClF1ZXLDqXRhcm8MUXVpbnRhbmEgUm9vEFNhbiBMdWlzIFBvdG9zw60HU2luYWxvYQZTb25vcmEHVGFiYXNjbwpUYW1hdWxpcGFzCFRsYXhjYWxhH1ZlcmFjcnV6IGRlIElnbmFjaW8gZGUgbGEgTGxhdmUIWXVjYXTDoW4JWmFjYXRlY2FzFSECMDACMDECMDICMDMCMDQCMDUCMDYCMDcCMDgCMDkCMTACMTECMTICMTMCMTQCMTUCMTYCMTcCMTgCMTkCMjACMjECMjICMjMCMjQCMjUCMjYCMjcCMjgCMjkCMzACMzECMzIUKwMhZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZGQCHQ88KwALAGQYAQUeX19Db250cm9sc1JlcXVpcmVQb3N0QmFja0tleV9fFgEFC2J0bkRlc2NhcmdhDqqieuU2FiAthMt135KqZUNzeSU=',
                '__VIEWSTATEGENERATOR' => 'BE1A6D2E',
                '__EVENTVALIDATION' => '/wEWKAKE2PypCALG/OLvBgLWk4iCCgLWk4SCCgLWk4CCCgLWk7yCCgLWk7iCCgLWk7SCCgLWk7CCCgLWk6yCCgLWk+iBCgLWk+SBCgLJk4iCCgLJk4SCCgLJk4CCCgLJk7yCCgLJk7iCCgLJk7SCCgLJk7CCCgLJk6yCCgLJk+iBCgLJk+SBCgLIk4iCCgLIk4SCCgLIk4CCCgLIk7yCCgLIk7iCCgLIk7SCCgLIk7CCCgLIk6yCCgLIk+iBCgLIk+SBCgLLk4iCCgLLk4SCCgLLk4CCCgLL+uTWBALa4Za4AgK+qOyRAQLI56b6CwL1/KjtBZK+wpecH6QQhL5+UE/1k5Cpji9I',
                'cboEdo' => '00',
                'rblTipo' => 'txt',
                'btnDescarga.x' => '31',
                'btnDescarga.y' => '8',
            ])->body();

        return Storage::disk('local')->put('zip_codes.zip', $contents);
    }

    /**
     * @return false
     */
    public function extractAndEncode(): bool
    {
        $zip = new ZipArchive;
        if ($zip->open(storage_path('app/zip_codes.zip')) === true) {
            $zip->extractTo(resource_path('data'));
            $zip->close();
            $strContent = file_get_contents(resource_path('data/CPdescarga.txt'));
            $strContent = mb_convert_encoding($strContent, 'UTF-8', 'iso-8859-1');
            $filename = resource_path('data/converted.txt');
            $fp = fopen($filename, 'wb');
            if (!$fp) {
                return false;
            }
            fwrite($fp, $strContent);
            fclose($fp);
            return true;
        } else {
            Log::error('Error en la apertura del archivo');
            return false;
        }


    }

    /**
     * Upsert Zip Codes from listener
     * @return bool
     */
    public function upsertZipCodes(): bool
    {
        $counter = 0;
        $zipArray = [];
        $file = fopen(resource_path('data/converted.txt'), 'r+');
        if(!$file){
            Log::error('Error opening file');
            return false;
        }
        while ($line = stream_get_line($file, 1024 * 1024, PHP_EOL)) {
            $counter++;
            //skip first text and columns headers
            if ($counter == 1 || $counter == 2) {
                continue;
            }

            $row = explode('|', trim($line));

            if (count($row) !== 15) {
                Log::error('Error en la linea ' . $counter . ': ' . $line);
                continue;
            }

            $zip = [
                'zip_code' => $row[0],
                'locality' => strtoupper($row[5]),
            ];

            $federalEntity = [
                'key' => (int)$row[7],
                'name' => strtoupper($row[4]),
                'code' => (int)$row[9] ?? null,
            ];

            $settlements = [
                'key' => (int)trim($row[12]),
                'name' => strtoupper($row[1]),
                'zone_type' => $row[13],
                'settlement_type' => [
                    'name' => ucfirst($row[2]),
                ],
            ];

            $municipality = [
                'key' => (int)$row[11],
                'name' => $row[3],
            ];


            if(!isset($zipArray[$zip['zip_code']])) {
                $zipArray[$zip['zip_code']] = [
                    'zip_code' => $zip['zip_code'],
                    'locality' => $zip['locality'],
                    'federal_entity' => $federalEntity,
                    'settlements' => [
                        $settlements,
                    ],
                    'municipality' => $municipality,
                ];
            } else {
                $zipArray[$zip['zip_code']]['federal_entity'] = $federalEntity;
                $zipArray[$zip['zip_code']]['settlements'][] = $settlements;
                $zipArray[$zip['zip_code']]['municipality'] = $municipality;
            }
        }
        fclose($file);

        $chunkedZipArray = array_chunk($zipArray, 1000);

        foreach ($chunkedZipArray as $chunk) {
           $this->zipCodeRepository->upsertZipCodes($chunk);
        }

        return true;
    }
}
