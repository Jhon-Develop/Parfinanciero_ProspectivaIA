<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\Expense;

class OpenAIService
{
    // Service configurations: OpenAI API key, base URL, max tokens, and cache timeout.
    protected $apiKey;
    protected $baseUrl = 'https://api.openai.com/v1';
    protected $maxTokens = 250;
    protected $cacheTimeout = 3600; // 1-hour cache

    // Constructor retrieves the OpenAI API key from configuration.
    public function __construct()
    {
        $this->apiKey = config('services.openai.key');
    }

    // Generates a unique cache key based on the method name and parameters.
    private function getCacheKey(string $method, array $params): string
    {
        return 'openai_' . $method . '_' . md5(serialize($params));
    }

    // Makes an API request to OpenAI's chat completion endpoint with the given input.
    private function makeOpenAIRequest(string $input): string
    {
        $cacheKey = $this->getCacheKey('analysis', ['input' => $input]);

        // Check if a cached result exists for the request.
        if ($cachedResult = Cache::get($cacheKey)) {
            Log::info('Retrieved analysis from cache', ['cache_key' => $cacheKey]);
            return $cachedResult;
        }

        try {
            // System-level instruction for the AI model.
            $systemPrompt = "Eres un experto analista financiero. Sé detallado pero conciso.";

            // Send a POST request to OpenAI's API with the input.
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => "Bearer {$this->apiKey}",
                    'Content-Type' => 'application/json',
                ])->post("{$this->baseUrl}/chat/completions", [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ["role" => "system", "content" => $systemPrompt],
                        ["role" => "user", "content" => $input],
                    ],
                    'max_tokens' => $this->maxTokens,
                    'temperature' => 0.7,
                ]);

            // Handle a successful API response.
            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');
                Cache::put($cacheKey, $content, $this->cacheTimeout);

                $tokens = $response->json('usage');
                Log::info('Token usage:', $tokens);

                return $content;
            }

            // Log errors for unsuccessful responses.
            Log::error('OpenAI API request failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return 'Error en el análisis.';
        } catch (\Exception $e) {
            // Handle and log exceptions during the API request.
            Log::error('Error in OpenAI request', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 'Error en el análisis.';
        }
    }

    // Retrieves and summarizes user expenses for a given date range.
    private function getExpenseSummary(int $userId, string $startDate, string $endDate): array
    {
        $cacheKey = $this->getCacheKey('expenses', [
            'user_id' => $userId,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);

        // Check for a cached summary of expenses.
        if ($cachedSummary = Cache::get($cacheKey)) {
            return $cachedSummary;
        }

        // Query the database to calculate the total amount by category.
        $expenses = Expense::where('user_id', $userId)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
            ->selectRaw('category, SUM(total_amount) as total_amount')
            ->groupBy('category')
            ->get()
            ->toArray();

        Cache::put($cacheKey, $expenses, $this->cacheTimeout);

        return $expenses;
    }

    // Formats the expense data into a textual input for AI analysis.
    private function formatExpenseInput(array $expenseSummary): string
    {
        if (empty($expenseSummary)) {
            return "No hay gastos registrados. Sugiere cómo empezar a registrar gastos.";
        }

        $total = 0;
        $summaryText = "Gastos:\n";

        foreach ($expenseSummary as $expense) {
            $amount = number_format($expense['total_amount'], 2);
            $summaryText .= "{$expense['category']}: $" . $amount . "\n";
            $total += $expense['total_amount'];
        }

        $summaryText .= "\nTotal: $" . number_format($total, 2) . "\n";
        $summaryText .= "Analiza distribución, optimización y tendencias. Sé conciso.";

        return $summaryText;
    }

    // Generates a prospective financial analysis for a user.
    public function generateProspectiveAnalysis(int $userId, string $startDate, string $endDate): string
    {
        $cacheKey = $this->getCacheKey('analysis', [
            'user_id' => $userId,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);

        // Check if analysis is already cached.
        if ($cachedAnalysis = Cache::get($cacheKey)) {
            Log::info('Retrieved analysis from cache', ['cache_key' => $cacheKey]);
            return $cachedAnalysis;
        }

        $expenseSummary = $this->getExpenseSummary($userId, $startDate, $endDate);
        $input = $this->formatExpenseInput($expenseSummary);
        $analysis = $this->makeOpenAIRequest($input);

        Cache::put($cacheKey, $analysis, $this->cacheTimeout);

        return $analysis;
    }

    // Generates a financial analysis for specified goals.
    public function generateFinancialAnalysis(array $financialGoals): string
    {
        $cacheKey = $this->getCacheKey('goals', ['goals' => $financialGoals]);

        // Check if goal analysis is already cached.
        if ($cachedAnalysis = Cache::get($cacheKey)) {
            Log::info('Retrieved goals analysis from cache', ['cache_key' => $cacheKey]);
            return $cachedAnalysis;
        }

        $input = "Metas financieras:\n";
        foreach ($financialGoals as $goal) {
            $input .= "{$goal['type']}: Meta $" . number_format($goal['target_amount'], 2) .
                ", Progreso $" . number_format($goal['current_progress'], 2) . "\n";
        }
        $input .= "\nAnaliza progreso y sugiere mejoras. Sé conciso.";

        $analysis = $this->makeOpenAIRequest($input);
        Cache::put($cacheKey, $analysis, $this->cacheTimeout);

        return $analysis;
    }

    // Generates an analysis based on user achievement history.
    public function generateAchievementAnalysis(array $achievementHistory): string
    {
        $cacheKey = $this->getCacheKey('achievement', ['history' => $achievementHistory]);

        // Check if achievement analysis is already cached.
        if ($cachedAnalysis = Cache::get($cacheKey)) {
            Log::info('Retrieved achievement analysis from cache', ['cache_key' => $cacheKey]);
            return $cachedAnalysis;
        }

        $input = $this->formatAchievementInput($achievementHistory);
        $analysis = $this->makeOpenAIRequest($input);

        Cache::put($cacheKey, $analysis, $this->cacheTimeout);

        return $analysis;
    }

    // Formats achievement history into a textual input for AI analysis.
    private function formatAchievementInput(array $achievementHistory): string
    {
        $input = "Achievement History:\n";
        foreach ($achievementHistory as $achievement) {
            $input .= "{$achievement['type']}: Target $" . number_format($achievement['target_amount'], 2) .
                ", Achieved $" . number_format($achievement['achieved_amount'], 2) .
                ", " . $achievement['achievement_percentage'] . "% complete\n";
        }
        $input .= "\nAnalyze progress, identify patterns, and suggest improvements. Be concise.";

        return $input;
    }
}
