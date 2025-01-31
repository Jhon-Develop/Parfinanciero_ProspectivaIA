<?php

namespace App\Http\Controllers;

use App\Services\ExpenseService;
use App\Services\GoalService;
use App\Services\OpenAIService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Parfinanciero API",
 *     version="1.0.0",
 *     description="This API generates a financial forecast based on a user's expenses and financial goals. It accepts the user's ID, along with optional start and end dates for the forecast. The response includes detailed analyses of the user's expenses and goals over the specified period. If no dates are provided, default values are used.",
 *     termsOfService="http://example.com/terms/",
 *     @OA\Contact(
 *         email="contact@parfinanciero.com"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 */


class FinancialForecastController extends Controller
{
    // Dependency injection for ExpenseService, GoalService, and OpenAIService.
    protected $expenseService;
    protected $goalService;
    protected $openAIService;

    // Constructor initializes dependencies for expense, goal, and AI-related operations.
    public function __construct(ExpenseService $expenseService, GoalService $goalService, OpenAIService $openAIService)
    {
        $this->expenseService = $expenseService;
        $this->goalService = $goalService;
        $this->openAIService = $openAIService;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/financial-forecast/{userId}",
     *     summary="Get financial forecast",
     *     description="Generates a financial forecast based on the user's expenses and goals.",
     *     tags={"Financial Forecast"},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         description="User ID to generate the financial forecast",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="startDate",
     *         in="query",
     *         required=false,
     *         description="Start date for the financial forecast",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="endDate",
     *         in="query",
     *         required=false,
     *         description="End date for the financial forecast",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Financial forecast generated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="userId", type="integer"),
     *             @OA\Property(property="startDate", type="string"),
     *             @OA\Property(property="endDate", type="string"),
     *             @OA\Property(property="expenseAnalysis", type="string"),
     *             @OA\Property(property="goalsAnalysis", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error generating the financial forecast",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */


    // Main function to generate financial forecast data for a specific user.
    public function getFinancialForecast($userId, Request $request)
    {
        // Default date range: January 1, 2020 to January 1, 2030.
        // If the startDate query parameter is provided, parse and set it; otherwise, use default.
        $startDate = $request->query('startDate')
            ? Carbon::parse($request->query('startDate'))->startOfDay()
            : Carbon::create(2020, 1, 1)->startOfDay();

        // If the endDate query parameter is provided, parse and set it; otherwise, use default.
        $endDate = $request->query('endDate')
            ? Carbon::parse($request->query('endDate'))->endOfDay()
            : Carbon::create(2030, 1, 1)->endOfDay();

        // Process expenses for the specified user and date range.
        $expenses = $this->expenseService->processExpenses($userId, $startDate, $endDate);

        // Process financial goals for the specified user.
        $goals = $this->goalService->processGoals($userId);

        // Generate prospective analysis for expenses using the OpenAIService.
        $expenseAnalysis = $this->openAIService->generateProspectiveAnalysis(
            $userId,
            $startDate->toDateString(),
            $endDate->toDateString()
        );

        // Generate financial analysis for goals using the OpenAIService.
        $goalsAnalysis = $this->openAIService->generateFinancialAnalysis($goals);

        // Return the response as a JSON object containing user ID, date range,
        // expense analysis, and goals analysis.
        return response()->json([
            'userId' => $userId,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
            'expenseAnalysis' => $expenseAnalysis,
            'goalsAnalysis' => $goalsAnalysis,
        ]);
    }
}
