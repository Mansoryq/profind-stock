<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TestSession;
use App\Models\UserAnswer;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

class TestHistory extends Component
{
    use WithPagination;

    public $statusFilter = 'all';
    public $timeFilter = 'all';
    public $paymentFilter = 'all';
    public $search = '';

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingTimeFilter()
    {
        $this->resetPage();
    }

    public function updatingPaymentFilter()
    {
        $this->resetPage();
    }

    public function getFilteredTestsProperty()
    {
        $query = TestSession::query()
            ->with([
                'user',
                'userAnswers' => function($q) {
                    $q->orderBy('created_at', 'desc');
            }])
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc');

        // Фильтр по статусу
        if ($this->statusFilter !== 'all') {
            switch ($this->statusFilter) {
                case 'completed':
                    $query->where('status', 'completed');
                    break;
                case 'pending':
                    $query->whereIn('status', ['started', 'in_progress']);
                    break;
                case 'failed':
                    $query->where('status', 'abandoned');
                    break;
            }
        }

        // Фильтр по времени
        if ($this->timeFilter !== 'all') {
            switch ($this->timeFilter) {
                case 'week':
                    $query->where('created_at', '>=', Carbon::now()->subWeek());
                    break;
                case 'month':
                    $query->where('created_at', '>=', Carbon::now()->subMonth());
                    break;
            }
        }

        // Фильтр по оплате
        if ($this->paymentFilter !== 'all') {
            switch ($this->paymentFilter) {
                case 'paid':
                    $query->whereIn('payment_status', ['completed', 'free']);
                    break;
                case 'unpaid':
                    $query->where('payment_status', 'pending');
                    break;
            }
        }

        // Поиск
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('session_id', 'like', '%' . $this->search . '%')
                  ->orWhere('selected_plan', 'like', '%' . $this->search . '%');
            });
        }

        return $query->paginate(15)->through(function ($session) {
            // Обновляем временные метрики перед отображением
            $session->updateTimeMetrics();

            return [
                'id' => $session->id,
                'session_id' => $session->session_id,
                'name' => $session->user?->name,
                'school' => $session->user?->school,
                'class' => $session->user?->class,
                'date' => $session->created_at->format('d.m.Y H:i'),
                'status' => $session->status,
                'result' => $this->formatResult($session),
                'action_url' => $this->getActionUrl($session),
                'action_text' => $this->getActionText($session),
                'is_paid' => in_array($session->payment_status, ['completed', 'free']),
                'payment_status' => $session->payment_status,
                'selected_plan' => $session->selected_plan ?? 'не выбран',
                'completion_percentage' => $session->completion_percentage ?? 0,
                'answered_questions' => $session->answered_questions ?? 0,
                'total_questions' => $session->total_questions ?? 180, // Default CliftonStrengths questions count
                'total_time_spent' => $session->total_time_spent ?? 0,
                'average_response_time' => $session->average_response_time ?? 0,
                'formatted_time' => $this->formatTime($session->total_time_spent),
                'answers_count' => $session->userAnswers->count(),
            ];
        });
    }

    private function formatResult($session)
    {
        if ($session->status === 'completed') {
            $percentage = round($session->completion_percentage, 1);
            return "{$session->answered_questions}/{$session->total_questions} вопросов ({$percentage}%)";
        } elseif ($session->status === 'in_progress') {
            return "{$session->answered_questions}/{$session->total_questions} вопросов";
        } else {
            return 'Не завершен';
        }
    }

    private function getActionUrl($session)
    {
        if (in_array($session->payment_status, ['completed', 'free'])) {
            return route('talent-test-results', ['sessionId' => $session->session_id]);
        } elseif (in_array($session->payment_status ,['processing', 'cancelled', 'failed', 'pending'])) {
            return route('payment-status', ['sessionId' => $session->session_id, 'plan' => $session->selected_plan ?? 'talents_spheres_professions']);
        } else {
            return route('talent-test');
        }
    }

    private function getActionText($session)
    {
        if (in_array($session->payment_status, ['completed', 'free'])) {
            return __('all.test_history.actions.results');
        } elseif ($session->payment_status === 'pending') {
            return __('all.test_history.actions.pay');
        } elseif (in_array($session->payment_status,['processing','cancelled','failed'])) {
            return __('all.test_history.actions.pay');
        } else {
            return __('all.test_history.actions.retry');
        }
    }

    private function formatTime($seconds)
    {
        if (!$seconds || $seconds <= 0) {
            return 'Нет данных';
        }

        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remainingSeconds = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%dч %dм %dс', $hours, $minutes, $remainingSeconds);
        } elseif ($minutes > 0) {
            return sprintf('%dм %dс', $minutes, $remainingSeconds);
        } else {
            return sprintf('%dс', $remainingSeconds);
        }
    }

    public function render()
    {
        return view('livewire.pages.test-history', [
            'filteredTests' => $this->getFilteredTestsProperty(),
        ]);
    }
}
