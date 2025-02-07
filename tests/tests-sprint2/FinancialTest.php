<?php

use PHPUnit\Framework\TestCase;
use App\Services\FinancialCalculator;
use App\Services\FinancialValidator;

class FinancialTest extends TestCase
{
    public function testCalculateInterest()
    {
        $calculator = new FinancialCalculator();
        $this->assertEquals(
            50, 
            $calculator->calculateInterest(1000, 0.05, 1),
            'El cálculo de interés debería ser correcto'
        );
    }

    public function testValidateMonetaryAmount()
    {
        $validator = new FinancialValidator();
        $this->assertTrue(
            $validator->isValidAmount('100.00'),
            'Debería aceptar montos con dos decimales'
        );
        $this->assertFalse(
            $validator->isValidAmount('100.999'),
            'No debería aceptar montos con más de dos decimales'
        );
    }
}