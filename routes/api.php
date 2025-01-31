<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FinancialForecastController;
use App\Http\Controllers\AIAnalysisController;

/**
 * Define a route for fetching a user's financial forecast.
 *
 * @route GET /v1/financial-forecast/{userId}
 * @param int $userId The ID of the user whose financial forecast will be retrieved.
 * @controller FinancialForecastController Handles the request to generate a financial forecast.
 */
Route::get('/v1/financial-forecast/{userId}', [FinancialForecastController::class, 'getFinancialForecast']);

/**
 * Define a route for generating a goals achievement analysis for a user.
 *
 * @route GET /v1/goals-achievement-analysis/{userId}
 * @param int $userId The ID of the user whose goals achievement analysis will be generated.
 * @controller AIAnalysisController Handles the request to analyze the user's goal achievements.
 */
Route::get('/v1/goals-achievement-analysis/{userId}', [AIAnalysisController::class, 'generateGoalsAchievementAnalysis']);
