<?php

namespace App\Repositories\Fake;

use App\Repositories\GoalRepositoryInterface;
use App\Models\Goal;

class FakeGoalRepository implements GoalRepositoryInterface
{
    /**
     * Retrieve all financial goals for a specific user.
     *
     * @param int $userId The ID of the user whose goals will be fetched.
     * @return array An array of goals associated with the user.
     */
    public function getGoals($userId)
    {
        // Query the database for goals linked to the provided user ID and convert the results to an array.
        return Goal::where('user_id', $userId)->get()->toArray();
    }

    /**
     * Simulate the achievement history for a user over a specific time range.
     *
     * @param int $userId The ID of the user whose achievement history will be retrieved.
     * @param string $startDate The start date of the time range.
     * @param string $endDate The end date of the time range.
     * @return array A simulated array of achievement records, including target and achieved amounts.
     */
    public function getAchievementHistory($userId, $startDate, $endDate)
    {
        // Provide simulated data for testing purposes.
        return [
            [
                'type' => 'Savings', // The type of financial goal.
                'target_amount' => 10000.00, // The target amount for the goal.
                'achieved_amount' => 8500.00, // The amount achieved so far.
                'achievement_percentage' => 85, // The percentage of the goal achieved.
                'start_date' => $startDate, // The start date of the achievement period.
                'end_date' => $endDate // The end date of the achievement period.
            ],
            [
                'type' => 'Debt Repayment', // Another example goal type.
                'target_amount' => 5000.00,
                'achieved_amount' => 4000.00,
                'achievement_percentage' => 80,
                'start_date' => $startDate,
                'end_date' => $endDate
            ],
            // Add additional simulated records as needed for testing or demonstration purposes.
        ];
    }
}
