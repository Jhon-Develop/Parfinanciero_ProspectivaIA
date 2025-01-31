<?php

namespace App\Services;

use App\Repositories\GoalRepositoryInterface;
use Illuminate\Support\Facades\Log;

class GoalService
{
    protected $repository;

    public function __construct(GoalRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Process goals for a user.
     *
     * @param int $userId
     * @return array
     */
    public function processGoals($userId)
    {
        try {
            // Use the repository to get goals instead of directly querying the model
            $goals = $this->repository->getGoals($userId);

            if (empty($goals)) {
                Log::warning("No goal data returned for user {$userId}");
            } else {
                Log::info("Found " . count($goals) . " goals for user {$userId}");
            }

            return $goals;
        } catch (\Exception $e) {
            Log::error("Error processing goals for user {$userId}: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get achievement history for a user within a given date range.
     *
     * @param int $userId
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getAchievementHistory($userId, $startDate, $endDate)
    {
        try {
            $achievementHistory = $this->repository->getAchievementHistory($userId, $startDate, $endDate);

            if (empty($achievementHistory)) {
                Log::info("No achievement history found for user {$userId} between {$startDate} and {$endDate}");
                return [];
            }

            Log::info("Found " . count($achievementHistory) . " achievement records for user {$userId}");

            return $achievementHistory;
        } catch (\Exception $e) {
            Log::error("Error retrieving achievement history for user {$userId}: " . $e->getMessage());
            return [];
        }
    }
}

