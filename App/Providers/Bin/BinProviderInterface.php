<?php

declare(strict_types=1);

namespace App\Providers\Bin;

interface BinProviderInterface
{
    /**
     * @param  int  $digits
     * @return bool
     */
    public function isEU(int $digits): bool;
}
