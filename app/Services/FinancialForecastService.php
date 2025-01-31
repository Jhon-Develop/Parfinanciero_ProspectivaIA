<?php

namespace App\Services;

use App\Services\ExpenseService;
use App\Services\GoalService;
use App\Services\OpenAIService;

class FinancialForecastService
{
    protected $expenseService;
    protected $goalService;
    protected $openAIService;

    /**
     * Constructor to initialize services used for financial forecasting.
     *
     * @param ExpenseService $expenseService Handles expense-related processing.
     * @param GoalService $goalService Handles goal-related processing.
     * @param OpenAIService $openAIService Handles AI-driven analysis and insights.
     */
    public function __construct(
        ExpenseService $expenseService,
        GoalService $goalService,
        OpenAIService $openAIService
    ) {
        $this->expenseService = $expenseService;
        $this->goalService = $goalService;
        $this->openAIService = $openAIService;
    }

    //Generates a financial forecast for a given user and date range.
    public function generateForecast($userId, $startDate, $endDate)
    {
        // Fetch user expenses within the specified date range
        $expenses = $this->expenseService->processExpenses($userId, $startDate, $endDate);

        // Retrieve the user's financial goals
        $goals = $this->goalService->processGoals($userId);

        // Retrieve the user's achievement history within the specified date range
        $achievementHistory = $this->goalService->getAchievementHistory($userId, $startDate, $endDate);

        // Generate expense analysis using OpenAI
        $expenseAnalysis = $this->openAIService->generateProspectiveAnalysis($userId, $startDate, $endDate);

        // Generate financial goals analysis using OpenAI
        $goalsAnalysis = $this->openAIService->generateFinancialAnalysis($goals);

        // Note: Achievement analysis generation is commented out. Uncomment if needed.
        // $achievementAnalysis = $this->openAIService->generateAchievementAnalysis($achievementHistory->toArray());

        // Return the forecast results as an array
        return [
            'userId' => $userId,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'expenseAnalysis' => $expenseAnalysis,
            'goalsAnalysis' => $goalsAnalysis,
            // 'achievementAnalysis' => $achievementAnalysis, // Optional: Include achievement analysis if uncommented
        ];
    }
}
