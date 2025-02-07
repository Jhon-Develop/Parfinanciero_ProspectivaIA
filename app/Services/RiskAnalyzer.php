<?php
// app/Services/RiskAnalyzer.php
namespace App\Services;

class RiskAnalyzer
{
    public function calculateRiskScore($clientData)
    {
        // Implementa la lógica real aquí
        return 50.0;
    }

    public function classifyRisk($score)
    {
        if ($score < 30) return 'bajo';
        if ($score < 70) return 'medio';
        return 'alto';
    }
}