<?php

namespace App\Repositories;

use Carbon\Carbon;

interface ExpenseRepositoryInterface
{
    /**
     * Get expenses for a user within a given date range.
     *
     * @param int $userId
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    public function getExpenses($userId, Carbon $startDate, Carbon $endDate);
}
