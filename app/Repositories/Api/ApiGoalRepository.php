<?php

namespace App\Repositories\Api;

use App\Repositories\GoalRepositoryInterface;
use Illuminate\Support\Facades\Http;

class ApiGoalRepository implements GoalRepositoryInterface
{
    public function getGoals($userId)
    {
        // Existing code...
    }

    public function getAchievementHistory($userId, $startDate, $endDate)
    {
        $response = Http::get(config('api.goals_url') . "/{$userId}/achievement-history", [
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to fetch achievement history from API');
    }
}
