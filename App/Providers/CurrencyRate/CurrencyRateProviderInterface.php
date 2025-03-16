<?php

declare(strict_types=1);

namespace App\Providers\CurrencyRate;

interface CurrencyRateProviderInterface
{
    /**
     * @param  string  $code
     * @return float
     */
    public function getCurrencyRate(string $code): float;
}
