<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\Profession;
use App\Models\Talent;
use App\Models\TestSession;
use App\Models\UserAnswer;
use App\Models\Answer;
use Illuminate\Support\Facades\Auth;

class ProfessionRecommendations extends Component
{
    public $testSession = null;
    public $recommendations = [];
    public $userTalentScores = [];
    public $testSessionId = null;

    public function mount($sessionId = null)
    {
        // Получаем session_id из параметра URL или из сессии
        $sessionIdToUse = $sessionId ?? session('last_test_session_id');
        
        if ($sessionIdToUse) {
            // Найдем TestSession по session_id
            $this->testSession = TestSession::where('session_id', $sessionIdToUse)
                ->where('user_id', Auth::id())
                ->with(['userAnswers' => function($query) {
                    $query->orderBy('question_id', 'asc');
                }])
                ->first();
        }
        
        // Если не найдена сессия, попробуем найти последнюю завершенную сессию пользователя
        if (!$this->testSession) {
            $this->testSession = TestSession::where('user_id', Auth::id())
                ->where('status', 'completed')
                ->with(['userAnswers' => function($query) {
                    $query->orderBy('question_id', 'asc');
                }])
                ->latest('completed_at')
                ->first();
        }

        if (!$this->testSession || $this->testSession->userAnswers->isEmpty()) {
            return;
        }

        // Проверяем статус оплаты - рекомендации профессий только для премиум и выше
        if (!$this->isPaidSession()) {
            return;
        }

        $this->testSessionId = $this->testSession->session_id;
        $this->calculateTalentScores();
        $this->calculateProfessionRecommendations();
    }

    /**
     * Проверяет, является ли сессия оплаченной (премиум или профессиональный план)
     */
    private function isPaidSession()
    {
        if (!$this->testSession) {
            return false;
        }

        // Проверяем статус оплаты
        if ($this->testSession->payment_status !== 'completed') {
            return false;
        }

        // Проверяем тарифный план - только премиум и профессиональный
        $allowedPlans = ['premium', 'professional'];
        return in_array($this->testSession->selected_plan, $allowedPlans);
    }

    private function calculateTalentScores()
    {
        $userAnswers = $this->testSession->userAnswers;
        
        // Get all talents and answers
        $talents = Talent::with('domain')->orderBy('id')->get();
        $answers = Answer::with('talent.domain')->orderBy('id')->get();
        
        // Group answers by question_id
        $questionScores = [];
        foreach ($userAnswers as $userAnswer) {
            $questionId = $userAnswer->question_id;
            if (!isset($questionScores[$questionId])) {
                $questionScores[$questionId] = 0;
            }
            $questionScores[$questionId] += $userAnswer->answer_value;
        }
        
        // Initialize talent scores
        $this->userTalentScores = [];
        foreach ($talents as $talent) {
            $this->userTalentScores[$talent->id] = 0;
        }
        
        // Calculate scores for each talent by grouping answers by talent
        foreach ($questionScores as $questionId => $score) {
            $questionIndex = $questionId - 1; // Convert to 0-based index (1-87 -> 0-86)
            if ($questionIndex >= 0 && $questionIndex < count($answers)) {
                $answer = $answers[$questionIndex];
                if ($answer->talent) {
                    // Add the score to the corresponding talent
                    $this->userTalentScores[$answer->talent->id] += $score;
                }
            }
        }
    }

    private function calculateProfessionRecommendations()
    {
        // Get all professions with their talent coefficients and spheres
        $professions = Profession::with(['talents', 'sphere'])->get();
        
        $recommendations = [];
        
        foreach ($professions as $profession) {
            $compatibilityScore = 0;
            $totalWeight = 0;
            $matchedTalents = 0;
            
            foreach ($profession->talents as $talent) {
                $userScore = $this->userTalentScores[$talent->id] ?? 0;
                $coefficient = $talent->pivot->coefficient;
                
                // Normalize user score (assuming max possible score per talent is around 15-20)
                $normalizedUserScore = min($userScore / 20, 1.0);
                
                // Calculate weighted compatibility
                $compatibilityScore += $normalizedUserScore * $coefficient;
                $totalWeight += $coefficient;
                
                // Count how many talents are matched (user score > 0)
                if ($userScore > 0) {
                    $matchedTalents++;
                }
            }
            
            // Calculate final compatibility percentage
            // Give bonus for having data on more talents
            $talentCoverageBonus = ($matchedTalents / count($profession->talents)) * 0.1; // 10% bonus for full coverage
            $baseCompatibility = $totalWeight > 0 ? ($compatibilityScore / $totalWeight) : 0;
            $finalCompatibility = ($baseCompatibility + $talentCoverageBonus) * 100;
            
            $recommendations[] = [
                'profession' => $profession,
                'compatibility_score' => round(min($finalCompatibility, 100), 1), // Cap at 100%
                'top_matching_talents' => $this->getTopMatchingTalents($profession, 3),
                'matched_talents_count' => $matchedTalents,
                'total_talents_count' => count($profession->talents),
                'sphere' => $profession->sphere ? $profession->sphere->name : 'Без категории',
                'sphere_data' => $profession->sphere ? [
                    'id' => $profession->sphere->id,
                    'name' => $profession->sphere->name,
                    'color' => $profession->sphere->color,
                    'icon' => $profession->sphere->icon,
                ] : null
            ];
        }
        
        // Sort by compatibility score (descending)
        usort($recommendations, function($a, $b) {
            return $b['compatibility_score'] <=> $a['compatibility_score'];
        });
        
        // Take top 10 recommendations
        $this->recommendations = array_slice($recommendations, 0, 10);
    }
    
    private function getTopMatchingTalents($profession, $limit = 3)
    {
        $matchingTalents = [];
        
        foreach ($profession->talents as $talent) {
            $userScore = $this->userTalentScores[$talent->id] ?? 0;
            $coefficient = $talent->pivot->coefficient;
            
            // Calculate matching score
            $normalizedUserScore = min($userScore / 20, 1.0);
            $matchingScore = $normalizedUserScore * $coefficient;
            
            $matchingTalents[] = [
                'talent' => $talent,
                'user_score' => $userScore,
                'coefficient' => $coefficient,
                'matching_score' => $matchingScore
            ];
        }
        
        // Sort by matching score
        usort($matchingTalents, function($a, $b) {
            return $b['matching_score'] <=> $a['matching_score'];
        });
        
        return array_slice($matchingTalents, 0, $limit);
    }

    public function render()
    {
        // Group recommendations by sphere
        $groupedRecommendations = [];
        
        foreach ($this->recommendations as $recommendation) {
            $sphereName = $recommendation['sphere'] ?? 'Без категории';
            
            if (!isset($groupedRecommendations[$sphereName])) {
                $groupedRecommendations[$sphereName] = [
                    'sphere' => $recommendation['sphere_data'] ?? null,
                    'professions' => []
                ];
            }
            
            $groupedRecommendations[$sphereName]['professions'][] = $recommendation;
        }
        
        return view('livewire.pages.profession-recommendations', [
            'recommendations' => $this->recommendations,
            'groupedRecommendations' => $groupedRecommendations,
        ]);
    }
}
