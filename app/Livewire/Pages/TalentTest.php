<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\Talent;
use App\Models\Answer;
use App\Models\TestSession;
use App\Models\UserAnswer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class TalentTest extends Component
{
    public $allQuestions = [];
    public $testSessionId; // ID сессии теста
    public $timePerQuestion = 20; // 20 seconds per question

    public function mount()
    {
        // Генерируем уникальный ID для сессии теста
        $this->testSessionId = Str::uuid()->toString();

        // Получаем все ответы (вопросы) из базы данных
        /** @var Answer[] $answers */
        $answers = Answer::with('talent')->orderBy('id')->get();

        // Добавляем все 120 вопросов в массив
        foreach ($answers as $index => $answer) {
            $this->allQuestions[] = [
                'id' => $answer->id,
                'question' => $answer->localizedQuestion,
                'talent_name' => $answer->talent ? $answer->talent->name : 'Unknown',
                'talent_id' => $answer->talent ? $answer->talent_id : null,
                'question_number' => $index + 1,
            ];
        }

        // Перемешиваем вопросы для случайного порядка
        shuffle($this->allQuestions);

        // Создаем запись TestSession
        TestSession::query()->create([
            'session_id' => $this->testSessionId,
            'user_id' => Auth::id() ?? 1,
            'payment_status' => 'pending',
            'selected_plan' => null,
            'total_questions' => count($this->allQuestions),
            'answered_questions' => 0,
            'completion_percentage' => 0,
            'status' => 'started',
            'started_at' => now(),
        ]);
    }

    public function submitTestResults($answers, $responseTimes)
    {
        // Декодируем данные с фронтенда
        $answersData = json_decode($answers, true);
        $responseTimesData = json_decode($responseTimes, true);

        // Сохраняем все ответы пакетом
        $answersToSave = [];

        foreach ($answersData as $index => $value) {
            if ($value !== null && isset($this->allQuestions[$index])) {
                $question = $this->allQuestions[$index];
                $responseTime = $responseTimesData[$index] ?? $this->timePerQuestion;

                $answersToSave[] = [
                    'user_id' => Auth::id() ?? 1,
                    'question_id' => $question['id'],
                    'test_session_id' => $this->testSessionId,
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
            UserAnswer::insert($answersToSave);
        }

        // Обновляем TestSession при завершении теста
        $testSession = TestSession::where('session_id', $this->testSessionId)->first();
        if ($testSession) {
            $testSession->update([
                'answered_questions' => count($answersToSave),
                'completion_percentage' => 100,
                'status' => 'completed',
                'completed_at' => now()
            ]);

            // Обновляем метрики времени если методы существуют
            if (method_exists($testSession, 'updateProgress')) {
                $testSession->updateProgress();
            }
            if (method_exists($testSession, 'updateTimeMetrics')) {
                $testSession->updateTimeMetrics();
            }
        }

        session()->flash('message', 'Ваш тест завершен! Выберите тарифный план для получения результатов.');
        session()->put('last_test_session_id', $this->testSessionId);

        return redirect()->route('payment-status', ['sessionId' => $this->testSessionId, 'plan' => 'talents_spheres_professions']);
    }

    public function render()
    {
        $totalQuestions = count($this->allQuestions);

        return view('livewire.pages.talent-test', [
            'allQuestions' => $this->allQuestions,
            'totalQuestions' => $totalQuestions,
            'timePerQuestion' => $this->timePerQuestion,
            'testSessionId' => $this->testSessionId,
        ]);
    }
}
