<?php

namespace App\Http\Controllers;

use App\Services\OpenAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;
use OpenApi\Annotations as OA;


class AIAnalysisController extends Controller
{
    protected $openAIService;


    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    //Expense
    // /**
    //  * @OA\Get(
    //  *     path="/api/analysis/{userId}",
    //  *     summary="Generar análisis de gastos",
    //  *     description="Genera un análisis prospectivo de los gastos del usuario utilizando inteligencia artificial.",
    //  *     tags={"Análisis de Gastos"},
    //  *     @OA\Parameter(
    //  *         name="userId",
    //  *         in="path",
    //  *         required=true,
    //  *         description="ID del usuario para generar el análisis",
    //  *         @OA\Schema(type="integer")
    //  *     ),
    //  *     @OA\Parameter(
    //  *         name="startDate",
    //  *         in="query",
    //  *         required=false,
    //  *         description="Fecha de inicio para el análisis",
    //  *         @OA\Schema(type="string", format="date")
    //  *     ),
    //  *     @OA\Parameter(
    //  *         name="endDate",
    //  *         in="query",
    //  *         required=false,
    //  *         description="Fecha de fin para el análisis",
    //  *         @OA\Schema(type="string", format="date")
    //  *     ),
    //  *     @OA\Response(
    //  *         response=200,
    //  *         description="Análisis generado correctamente",
    //  *         @OA\JsonContent(
    //  *             type="object",
    //  *             @OA\Property(property="userId", type="integer"),
    //  *             @OA\Property(property="startDate", type="string"),
    //  *             @OA\Property(property="endDate", type="string"),
    //  *             @OA\Property(property="analysis", type="string")
    //  *         )
    //  *     ),
    //  *     @OA\Response(
    //  *         response=404,
    //  *         description="No se encontraron datos para el rango de fechas indicado",
    //  *         @OA\JsonContent(
    //  *             @OA\Property(property="error", type="string")
    //  *         )
    //  *     ),
    //  *     @OA\Response(
    //  *         response=500,
    //  *         description="Error al generar el análisis",
    //  *         @OA\JsonContent(
    //  *             @OA\Property(property="error", type="string")
    //  *         )
    //  *     )
    //  * )
    //  */


    public function generateAnalysis(Request $request, $userId)
    {
        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');

        // Obtener el resumen de gastos desde la base de datos
        $expenseSummary = Expense::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->select('category', DB::raw('SUM(total_amount) as total_amount'))
            ->groupBy('category')
            ->get();

        // Si no hay gastos encontrados
        if ($expenseSummary->isEmpty()) {
            return response()->json(['error' => 'No se encontraron datos para el rango de fechas indicado.'], 404);
        }

        // Generar el análisis con IA
        $analysis = $this->openAIService->generateProspectiveAnalysis($userId, $startDate, $endDate);

        return response()->json([
            'userId' => $userId,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'analysis' => $analysis,
        ]);
    }


    //Goals

    //Current financial goals
    // /**
    //  * @OA\Get(
    //  *     path="/api/goals-analysis/{userId}",
    //  *     summary="Generar análisis de objetivos financieros",
    //  *     description="Genera un análisis financiero basado en los objetivos actuales del usuario.",
    //  *     tags={"Análisis de Objetivos Financieros"},
    //  *     @OA\Parameter(
    //  *         name="userId",
    //  *         in="path",
    //  *         required=true,
    //  *         description="ID del usuario para generar el análisis de objetivos financieros",
    //  *         @OA\Schema(type="integer")
    //  *     ),
    //  *     @OA\Response(
    //  *         response=200,
    //  *         description="Análisis de objetivos financieros generado correctamente",
    //  *         @OA\JsonContent(
    //  *             type="object",
    //  *             @OA\Property(property="userId", type="integer"),
    //  *             @OA\Property(property="goals", type="array", @OA\Items(type="string")),
    //  *             @OA\Property(property="analysis", type="string")
    //  *         )
    //  *     ),
    //  *     @OA\Response(
    //  *         response=500,
    //  *         description="Error al generar el análisis de objetivos financieros",
    //  *         @OA\JsonContent(
    //  *             @OA\Property(property="error", type="string")
    //  *         )
    //  *     )
    //  * )
    //  */

    public function generateGoalsAnalysis(Request $request, $userId)
    {
        // Obtén los objetivos financieros actuales desde el microservicio de objetivos
        $response = Http::get("http://your-financial-goals-microservice/api/goals/{$userId}/current");

        if ($response->failed()) {
            return response()->json(['error' => 'Error al obtener los objetivos financieros.'], 500);
        }

        $financialGoals = $response->json();

        // Genera el análisis con IA basado en los objetivos financieros actuales
        $analysis = $this->openAIService->generateFinancialAnalysis($financialGoals['goals']);

        return response()->json([
            'userId' => $userId,
            'goals' => $financialGoals['goals'],
            'analysis' => $analysis,
        ]);
    }


    //history of goal achievements

    // /**
    //  * @OA\Get(
    //  *     path="/api/goals-achievement/{userId}",
    //  *     summary="Generar análisis de logros de objetivos financieros",
    //  *     description="Genera un análisis basado en el historial de logros de metas del usuario.",
    //  *     tags={"Análisis de Logros de Objetivos Financieros"},
    //  *     @OA\Parameter(
    //  *         name="userId",
    //  *         in="path",
    //  *         required=true,
    //  *         description="ID del usuario para generar el análisis de logros",
    //  *         @OA\Schema(type="integer")
    //  *     ),
    //  *     @OA\Parameter(
    //  *         name="startDate",
    //  *         in="query",
    //  *         required=false,
    //  *         description="Fecha de inicio para el historial de logros",
    //  *         @OA\Schema(type="string", format="date")
    //  *     ),
    //  *     @OA\Parameter(
    //  *         name="endDate",
    //  *         in="query",
    //  *         required=false,
    //  *         description="Fecha de fin para el historial de logros",
    //  *         @OA\Schema(type="string", format="date")
    //  *     ),
    //  *     @OA\Response(
    //  *         response=200,
    //  *         description="Análisis de logros de objetivos financieros generado correctamente",
    //  *         @OA\JsonContent(
    //  *             type="object",
    //  *             @OA\Property(property="userId", type="integer"),
    //  *             @OA\Property(property="achievementHistory", type="array", @OA\Items(type="string")),
    //  *             @OA\Property(property="analysis", type="string")
    //  *         )
    //  *     ),
    //  *     @OA\Response(
    //  *         response=500,
    //  *         description="Error al generar el análisis de logros de objetivos financieros",
    //  *         @OA\JsonContent(
    //  *             @OA\Property(property="error", type="string")
    //  *         )
    //  *     )
    //  * )
    //  */

    public function generateGoalsAchievementAnalysis(Request $request, $userId)
    {
        // Obtén el historial de logros de metas del microservicio de logros
        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');

        $url = "http://your-financial-goals-microservice/api/goals/{$userId}/achievement-history";

        // Agregar los parámetros opcionales (startDate y endDate) si están presentes
        $queryParams = [];
        if ($startDate) {
            $queryParams['startDate'] = $startDate;
        }
        if ($endDate) {
            $queryParams['endDate'] = $endDate;
        }

        $response = Http::get($url, $queryParams);

        if ($response->failed()) {
            return response()->json(['error' => 'Error al obtener el historial de logros de metas.'], 500);
        }

        $achievementHistory = $response->json();

        // Genera el análisis con IA basado en el historial de logros
        $analysis = $this->openAIService->generateAchievementAnalysis($achievementHistory['achievementHistory']);

        return response()->json([
            'userId' => $userId,
            'achievementHistory' => $achievementHistory['achievementHistory'],
            'analysis' => $analysis,
        ]);
    }
}
