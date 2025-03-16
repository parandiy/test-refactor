<?php

declare(strict_types=1);

use App\Providers\Bin\LookupBinListProvider;
use App\Providers\CurrencyRate\ExchangeRatesApiProvider;
use App\Services\CommissionCalculator;
use App\Services\Reader;

require __DIR__.'/vendor/autoload.php';

$config = include __DIR__.'/config/app.php';

$reader = new Reader();
$calculator = new CommissionCalculator(
    new ExchangeRatesApiProvider($config['exchange_rates_access_key']),
    new LookupBinListProvider()
);

$data = $reader->read($argv[1]);

foreach ($data as $row) {
    try {
        $calculator->calculate($row);
    } catch (\Exception $e) {
        echo 'Unable to calculate: '.$e->getMessage();
    }
    echo "\n";
}
