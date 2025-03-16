<?php

declare(strict_types=1);

use App\Dto\TransactionDataDto;
use App\Providers\Bin\BinProviderInterface;
use App\Providers\CurrencyRate\CurrencyRateProviderInterface;
use App\Services\CommissionCalculator;
use PHPUnit\Framework\TestCase;

final class CommissionCalculatorTest extends TestCase
{
    private CurrencyRateProviderInterface $currencyRateProviderEur;
    private CurrencyRateProviderInterface $currencyRateProviderGbp;
    private CurrencyRateProviderInterface $currencyRateProviderUsd;
    private BinProviderInterface $binProviderEu;
    private BinProviderInterface $binProviderNotEu;

    protected function setUp(): void
    {
        $this->currencyRateProviderEur = $this->createStub(CurrencyRateProviderInterface::class);
        $this->currencyRateProviderEur->method('getCurrencyRate')->willReturn(1.00);

        $this->currencyRateProviderGbp = $this->createStub(CurrencyRateProviderInterface::class);
        $this->currencyRateProviderGbp->method('getCurrencyRate')->willReturn(0.843917);

        $this->currencyRateProviderUsd = $this->createStub(CurrencyRateProviderInterface::class);
        $this->currencyRateProviderUsd->method('getCurrencyRate')->willReturn(1.092239);

        $this->binProviderEu = $this->getMockBuilder(BinProviderInterface::class)->getMock();
        $this->binProviderEu->method('isEU')->willReturn(true);

        $this->binProviderNotEu = $this->getMockBuilder(BinProviderInterface::class)->getMock();
        $this->binProviderNotEu->method('isEU')->willReturn(false);
    }

    public function test_calculate_eur_for_eu(): void
    {
        $transactionData = new TransactionDataDto(123456, 1000.00, 'EUR');

        $calculator = new CommissionCalculator($this->currencyRateProviderEur, $this->binProviderEu);

        $this->assertEquals(10.0, $calculator->calculate($transactionData));
    }

    public function test_calculate_non_eur_for_eu(): void
    {
        $transactionData = new TransactionDataDto(123456, 1000.00, 'GBP');

        $calculator = new CommissionCalculator($this->currencyRateProviderGbp, $this->binProviderEu);

        $this->assertEquals(11.85, $calculator->calculate($transactionData));
    }

    public function test_calculate_eur_for_not_eu(): void
    {
        $transactionData = new TransactionDataDto(123456, 1000.00, 'EUR');

        $calculator = new CommissionCalculator($this->currencyRateProviderEur, $this->binProviderNotEu);

        $this->assertEquals(20.0, $calculator->calculate($transactionData));
    }

    public function test_calculate_non_eur_for_non_eu(): void
    {
        $transactionData = new TransactionDataDto(123456, 1000.00, 'GBP');

        $calculator = new CommissionCalculator($this->currencyRateProviderGbp, $this->binProviderNotEu);

        $this->assertEquals(23.7, $calculator->calculate($transactionData));
    }

    public function test_always_round_to_greater(): void
    {
        $transactionData = new TransactionDataDto(41417360, 130.00, 'USD');

        $calculator = new CommissionCalculator($this->currencyRateProviderUsd, $this->binProviderEu);

        $this->assertEquals(1.2, $calculator->calculate($transactionData));
    }
}
