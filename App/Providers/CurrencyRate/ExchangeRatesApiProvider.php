<?php

declare(strict_types=1);

namespace App\Providers\CurrencyRate;

class ExchangeRatesApiProvider implements CurrencyRateProviderInterface
{
    const API_URL = 'https://api.exchangeratesapi.io/v1/latest?format=1';

    /** @var array $data */
    private array $data;

    /**
     * @param  string  $accessKey
     */
    public function __construct(private string $accessKey)
    {
    }

    /**
     * @param  string  $code
     * @return float
     */
    public function getCurrencyRate(string $code): float
    {
        if (empty($this->data)) {
            $this->load();
        }

        return $this->data[$code];
    }

    /**
     * @return void
     */
    private function load(): void
    {
        $data = json_decode(file_get_contents(self::API_URL.'&access_key='.$this->accessKey), true);
        foreach ($data['rates'] as $code => $rate) {
            $this->data[$code] = $rate;
        }
    }
}
