<?php

namespace App\Repositories\Api;

use App\Repositories\ExpenseRepositoryInterface;
use Illuminate\Support\Facades\Http;

class ApiExpenseRepository implements ExpenseRepositoryInterface
{
    public function getExpenses($userId, $startDate, $endDate)
    {
        $response = Http::get(config('api.expenses_url') . "/{$userId}/summary", [
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to fetch expenses from API');
    }
}
