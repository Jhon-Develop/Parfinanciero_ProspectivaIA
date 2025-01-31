<?php

namespace App\Services;

use App\Repositories\ExpenseRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ExpenseService
{
    protected $repository;

    // Constructor: Injects the repository interface to decouple the service from direct data handling.
    public function __construct(ExpenseRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Processes expenses for a specific user within a given date range.
     *
     * @param int $userId The ID of the user whose expenses will be processed.
     * @param Carbon $startDate The start date for the expense query.
     * @param Carbon $endDate The end date for the expense query.
     * @return array An array of expenses for the specified user and date range.
     */
    public function processExpenses($userId, Carbon $startDate, Carbon $endDate)
    {
        try {
            // Log the start of the expense processing with user and date range details.
            Log::info("Processing expenses for user {$userId}", [
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString()
            ]);

            // Retrieve expenses using the repository for the specified user and date range.
            $expenses = $this->repository->getExpenses($userId, $startDate, $endDate);

            // Log a message if no expenses were found.
            if (empty($expenses)) {
                Log::info("No expenses found for user {$userId} between {$startDate} and {$endDate}");
            } else {
                // Log the number of expenses retrieved.
                Log::info("Found " . count($expenses) . " expenses for user {$userId}");
            }

            // Return the retrieved expenses.
            return $expenses;
        } catch (\Exception $e) {
            // Log the error details, including the user ID and date range, for debugging purposes.
            Log::error("Error processing expenses: " . $e->getMessage(), [
                'user_id' => $userId,
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return an empty array in case of an error.
            return [];
        }
    }
}
