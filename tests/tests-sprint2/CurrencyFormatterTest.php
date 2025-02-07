<?php

use PHPUnit\Framework\TestCase;
use App\Services\CurrencyFormatter;

class CurrencyFormatterTest extends TestCase
{
    public function testFormatCurrency()
    {
        $formatter = new CurrencyFormatter();
        $this->assertEquals(
            'S/ 1,000.00',
            $formatter->format(1000),
            'Debería formatear correctamente la moneda en soles'
        );
    }
}