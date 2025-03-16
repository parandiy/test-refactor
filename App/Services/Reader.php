<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\TransactionDataDto;

class Reader
{
    /**
     * @param  string  $source
     * @return TransactionDataDto[]
     * @throws \Exception
     */
    public function read(string $source): array
    {
        if(!file_exists($source)) {
            throw new \Exception("File does not exist");
        }

        $data = explode("\n", file_get_contents($source));

        $results = [];
        foreach ($data as $line) {
            if (empty($line)) {
                break;
            }

            $rowData = json_decode($line, true);

            $results[] = new TransactionDataDto(
                (int)$rowData['bin'],
                (float)$rowData['amount'],
                (string)$rowData['currency']
            );
        }

        return $results;
    }
}
