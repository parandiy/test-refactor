<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\TransactionDataDto;
use App\Providers\Bin\BinProviderInterface;
use App\Providers\CurrencyRate\CurrencyRateProviderInterface;

readonly class CommissionCalculator
{
    /**
     * @param  CurrencyRateProviderInterface  $currencyRateProvider
     * @param  BinProviderInterface  $binProvider
     */
    public function __construct(
        private CurrencyRateProviderInterface $currencyRateProvider,
        private BinProviderInterface $binProvider
    ) {
    }

    /**
     * @param  TransactionDataDto  $dataDto
     * @return float
     */
    public function calculate(TransactionDataDto $dataDto): float
    {
        $rate = $this->currencyRateProvider->getCurrencyRate($dataDto->getCurrency());

        $amountFixed = $dataDto->getAmount();

        if ($dataDto->getCurrency() !== 'EUR' || $rate > 0) {
            $amountFixed = $dataDto->getAmount() / $rate;
        }

        $amountFixed = $amountFixed * ($this->binProvider->isEU($dataDto->getBin()) ? 0.01 : 0.02);

        return $this->roundUp($amountFixed);
    }

    /**
     * @param $value
     * @return float
     */
    private function roundUp($value): float
    {
        $pow = pow(10, 2);
        return (ceil($pow * $value) + ceil($pow * $value - ceil($pow * $value))) / $pow;
    }
}
