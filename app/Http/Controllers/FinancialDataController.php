<?php

namespace App\Http\Controllers;

use App\Services\ExpenseService;
use App\Services\GoalService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;


class FinancialDataController extends Controller
{
    protected $expenseService;
    protected $goalService;

    /**
     * Constructor to initialize services for handling financial data.
     *
     * @param ExpenseService $expenseService Service for processing user expenses.
     * @param GoalService $goalService Service for processing user financial goals.
     */
    public function __construct(ExpenseService $expenseService, GoalService $goalService)
    {
        $this->expenseService = $expenseService;
        $this->goalService = $goalService;
    }

    /**
     * Retrieves financial data, including expenses and goals, for a specific user.
     *
     * @param Request $request HTTP request containing optional start_date and end_date.
     * @param int $userId The ID of the user whose data is being retrieved.
     * @return \Illuminate\Http\JsonResponse JSON response with expenses and goals data.
     */

    // /**
    //  * @OA\Get(
    //  *     path="/api/financial-data/{userId}",
    //  *     summary="Obtener datos financieros del usuario",
    //  *     description="Obtiene los datos de gastos y objetivos financieros para un usuario específico.",
    //  *     tags={"Datos Financieros"},
    //  *     @OA\Parameter(
    //  *         name="userId",
    //  *         in="path",
    //  *         required=true,
    //  *         description="ID del usuario para obtener los datos financieros",
    //  *         @OA\Schema(type="integer")
    //  *     ),
    //  *     @OA\Parameter(
    //  *         name="start_date",
    //  *         in="query",
    //  *         required=false,
    //  *         description="Fecha de inicio para los datos de gastos",
    //  *         @OA\Schema(type="string", format="date")
    //  *     ),
    //  *     @OA\Parameter(
    //  *         name="end_date",
    //  *         in="query",
    //  *         required=false,
    //  *         description="Fecha de fin para los datos de gastos",
    //  *         @OA\Schema(type="string", format="date")
    //  *     ),
    //  *     @OA\Response(
    //  *         response=200,
    //  *         description="Datos financieros obtenidos correctamente",
    //  *         @OA\JsonContent(
    //  *             type="object",
    //  *             @OA\Property(property="expenses", type="array", @OA\Items(type="object")),
    //  *             @OA\Property(property="goals", type="array", @OA\Items(type="object"))
    //  *         )
    //  *     ),
    //  *     @OA\Response(
    //  *         response=500,
    //  *         description="Error al obtener los datos financieros",
    //  *         @OA\JsonContent(
    //  *             @OA\Property(property="error", type="string")
    //  *         )
    //  *     )
    //  * )
    //  */

    public function getData(Request $request, $userId)
    {
        // Get start and end dates from the request or set default values
        $startDate = $request->input('start_date', now()->subMonths(3)->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        // Process user expenses for the specified date range
        $expenses = $this->expenseService->processExpenses($userId, $startDate, $endDate);

        // Process user financial goals
        $goals = $this->goalService->processGoals($userId);

        // Return the expenses and goals as a JSON response
        return response()->json([
            'expenses' => $expenses,
            'goals' => $goals
        ]);
    }
}
