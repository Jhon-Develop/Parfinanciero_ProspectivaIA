<?php

use PHPUnit\Framework\TestCase;
use App\Services\RiskAnalyzer;

class RiskAnalysisTest extends TestCase
{
    private $riskAnalyzer;

    protected function setUp(): void
    {
        $this->riskAnalyzer = new RiskAnalyzer();
    }

    public function testCalculateRiskScore()
    {
        $clientData = [
            'income' => 5000,
            'debt' => 1000,
            'credit_history' => 'good',
            'payment_history' => []
        ];

        $riskScore = $this->riskAnalyzer->calculateRiskScore($clientData);
        $this->assertIsFloat($riskScore);
        $this->assertGreaterThanOrEqual(0, $riskScore);
        $this->assertLessThanOrEqual(100, $riskScore);
    }

    public function testRiskClassification()
    {
        $this->assertEquals('bajo', $this->riskAnalyzer->classifyRisk(20));
        $this->assertEquals('medio', $this->riskAnalyzer->classifyRisk(50));
        $this->assertEquals('alto', $this->riskAnalyzer->classifyRisk(80));
    }
}