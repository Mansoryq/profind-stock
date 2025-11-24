<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Answer;
use App\Models\TestSession;
use App\Models\UserAnswer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class TalentTestController extends Controller
{
    public function submitTestResults(Request $request): JsonResponse
    {
        try {
            $testSessionId = $request->input('testSessionId');
            $answers = $request->input('answers', []);
            $responseTimes = $request->input('responseTimes', []);

            // Получаем все вопросы в том же порядке, что и в Livewire компоненте
            /** @var Answer[] $allAnswers */
            $allAnswers = Answer::with('talent')->orderBy('id')->get();
            $allQuestions = [];

            foreach ($allAnswers as $index => $answer) {
                $allQuestions[] = [
                    'id' => $answer->id,
                    'question' => $answer->question,
                    'talent_name' => $answer->talent ? $answer->talent->name : 'Unknown',
                    'talent_id' => $answer->talent ? $answer->talent->id : null,
                    'question_number' => $index + 1,
                ];
            }

            // Сохраняем все ответы пакетом
            $answersToSave = [];

            foreach ($answers as $index => $value) {
                if ($value !== null && isset($allQuestions[$index])) {
                    $question = $allQuestions[$index];
                    $responseTime = $responseTimes[$index] ?? 20; // default 20 seconds

                    $answersToSave[] = [
                        'user_id' => Auth::id() ?? 1,
                        'question_id' => $question['id'],
                        'test_session_id' => $testSessionId,
                        'answer_value' => $value,
                        'response_time_seconds' => $responseTime,
                        'answered_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // Вставляем все ответы одним запросом
            if (!empty($answersToSave)) {
                UserAnswer::query()->insert($answersToSave);
            }

            // Обновляем TestSession при завершении теста
            $testSession = TestSession::where('session_id', $testSessionId)->first();
            if ($testSession) {
                $testSession->update([
                    'answered_questions' => count($answersToSave),
                    'completion_percentage' => 100,
                    'status' => 'completed',
                    'completed_at' => now(),
                    'selected_plan' => 'talents_spheres_professions'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Тест успешно завершен!',
                'redirect_url' => route('payment-status', ['sessionId' => $testSessionId, 'plan' => 'talents_spheres_professions'])
            ]);

        } catch (\Exception $e) {
            // \Log::error('Error submitting test results: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Произошла ошибка при сохранении результатов. Попробуйте еще раз.'
            ], 500);
        }
    }
}
