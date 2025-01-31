<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Goal;

class GoalSeeder extends Seeder
{
    public function run()
    {
        $types = ['Savings', 'Debt Repayment', 'Investment'];

        foreach (range(1, 5) as $userId) {
            foreach ($types as $type) {
                $targetAmount = rand(100000, 1000000) / 100;
                $currentProgress = rand(0, $targetAmount);
                Goal::create([
                    'user_id' => $userId,
                    'type' => $type,
                    'target_amount' => $targetAmount,
                    'start_date' => now()->subMonths(6),
                    'target_date' => now()->addMonths(6),
                    'status' => $currentProgress >= $targetAmount ? 'Completed' : 'In Progress',
                    'current_progress' => $currentProgress,
                ]);
            }
        }
    }
}
