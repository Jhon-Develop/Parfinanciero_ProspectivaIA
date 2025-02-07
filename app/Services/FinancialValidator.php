<?php
// app/Services/FinancialValidator.php
namespace App\Services;

class FinancialValidator
{
    public function isValidAmount($amount)
    {
        return preg_match('/^\d+(\.\d{1,2})?$/', $amount) === 1;
    }
}