<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expense;
use Carbon\Carbon;

class ExpenseSeeder extends Seeder
{
    public function run()
    {
        $categories = ['Groceries', 'Utilities', 'Entertainment', 'Transportation'];

        foreach (range(1, 5) as $userId) {
            foreach ($categories as $category) {
                // Generar una fecha de inicio aleatoria en un rango de los últimos 6 meses
                $startDate = Carbon::now()->subMonths(rand(1, 6))->subDays(rand(0, 30));

                // Generar una fecha de fin aleatoria, que puede ser después de la fecha de inicio o incluso en el futuro
                $endDate = (clone $startDate)->addDays(rand(1, 60));

                Expense::create([
                    'user_id' => $userId,
                    'category' => $category,
                    'total_amount' => rand(5000, 50000) / 100,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ]);
            }
        }
    }
}
