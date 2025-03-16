<?php

declare(strict_types=1);

use App\Dto\TransactionDataDto;
use App\Services\Reader;
use PHPUnit\Framework\TestCase;

final class ReaderTest extends TestCase
{
    private Reader $reader;

    protected function setUp(): void
    {
        $this->reader = new Reader();
    }

    public function test_read_success(): void
    {
        $data = $this->reader->read('input.txt');

        $this->assertInstanceOf(TransactionDataDto::class, $data[0]);
    }

    public function test_file_not_exists(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('File does not exist');

        $this->reader->read('wrong.txt');
    }
}
