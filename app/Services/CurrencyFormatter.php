<?php
// app/Services/CurrencyFormatter.php
namespace App\Services;

class CurrencyFormatter
{
    public function format($amount)
    {
        return 'S/ ' . number_format($amount, 2);
    }
}