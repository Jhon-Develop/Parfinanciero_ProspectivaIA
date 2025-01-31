<?php

namespace App\Repositories\Fake;

use App\Repositories\ExpenseRepositoryInterface;
use App\Models\Expense;
use Carbon\Carbon;

class FakeExpenseRepository implements ExpenseRepositoryInterface
{
    /**
     * Retrieves expenses for a specific user within a given date range.
     *
     * @param int $userId The ID of the user whose expenses will be fetched.
     * @param Carbon $startDate The start date for filtering expenses.
     * @param Carbon $endDate The end date for filtering expenses.
     * @return array An array of expenses that match the specified criteria.
     */
    public function getExpenses($userId, Carbon $startDate, Carbon $endDate)
    {
        return Expense::where('user_id', $userId)
            ->where(function($query) use ($startDate, $endDate) {
                // Fetch expenses whose start date or end date falls within the range.
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function($q) use ($startDate, $endDate) {
                        // Include expenses that span across the entire range.
                        $q->where('start_date', '<=', $startDate)
                          ->where('end_date', '>=', $endDate);
                    });
            })
            ->get()
            ->toArray(); // Convert the result to an array.
    }
}
