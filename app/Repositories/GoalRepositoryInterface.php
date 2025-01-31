<?php

namespace App\Repositories;

interface GoalRepositoryInterface
{
    /**
     * Get goals for a user.
     *
     * @param int $userId
     * @return array
     */
    public function getGoals($userId);

    /**
     * Get achievement history for a user within a given date range.
     *
     * @param int $userId
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getAchievementHistory($userId, $startDate, $endDate);
}
