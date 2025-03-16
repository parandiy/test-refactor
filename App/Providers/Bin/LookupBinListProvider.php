<?php

declare(strict_types=1);

namespace App\Providers\Bin;

class LookupBinListProvider implements BinProviderInterface
{
    const API_URL = 'https://lookup.binlist.net/';

    /** @var string[] */
    private array $countries = [
        'AT',
        'BE',
        'BG',
        'CY',
        'CZ',
        'DE',
        'DK',
        'EE',
        'ES',
        'FI',
        'FR',
        'GR',
        'HR',
        'HU',
        'IE',
        'IT',
        'LT',
        'LU',
        'LV',
        'MT',
        'NL',
        'PL',
        'PT',
        'RO',
        'SE',
        'SI',
        'SK'
    ];

    /**
     * @param  int  $digits
     * @return bool
     * @throws \Exception
     */
    public function isEU(int $digits): bool
    {
        $data = $this->load($digits);

        return in_array($data['country']['alpha2'], $this->countries, true);
    }

    /**
     * @param $digits
     * @return array
     * @throws \Exception
     */
    private function load($digits): array
    {
        $binResults = file_get_contents(self::API_URL.$digits);

        if (empty($binResults)) {
            throw new \Exception('Bin result is empty');
        }

        return json_decode($binResults, true);
    }
}
