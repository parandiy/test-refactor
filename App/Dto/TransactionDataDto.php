<?php

declare(strict_types=1);

namespace App\Dto;

readonly class TransactionDataDto
{
    /**
     * @param  int  $bin
     * @param  float  $amount
     * @param  string  $currency
     */
    public function __construct(
        private int $bin,
        private float $amount,
        private string $currency
    ) {
    }

    /**
     * @return int
     */
    public function getBin(): int
    {
        return $this->bin;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }
}
