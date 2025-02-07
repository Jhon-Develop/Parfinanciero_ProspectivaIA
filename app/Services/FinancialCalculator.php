<?php
// app/Services/FinancialCalculator.php
namespace App\Services;

class FinancialCalculator
{
    public function calculateInterest($principal, $rate, $time)
    {
        return $principal * $rate * $time;
    }
}