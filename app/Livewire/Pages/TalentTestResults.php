<?php

namespace App\Livewire\Pages;

use App\Models\Intellect;
use App\Models\Profession;
use App\Models\Sphere;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use App\Models\Answer;
use App\Models\Talent;
use App\Models\TestSession;
use Illuminate\Support\Facades\Auth;

class TalentTestResults extends Component
{
    public $userResults = [];
    public $userIntellectResults = [];
    public $userResultsCopy = [];
    public $domains = [];
    public $domainScores = [];
    public $topStrengths = [];
    public $averageResponseTime = 0;
    public $testSession = null;
    public $testSessionId = null;
    public $totalTimeSpent = 0;
    public $testDate = null;
    public $answersCount = 0;
    public $localizedDomains = [];
    public $domainsDescription = [];

    public function mount($sessionId = null)
    {
        // Получаем session_id из параметра URL или из сессии
        $sessionIdToUse = $sessionId ?? session("last_test_session_id");

        if ($sessionIdToUse) {
            // Найдем TestSession по session_id
            $this->testSession = TestSession::where(
                "session_id",
                $sessionIdToUse
            )
                ->where("user_id", Auth::id())
                ->with([
                    "userAnswers" => function ($query) {
                        $query->orderBy("created_at", "asc");
                    },
                ])
                ->first();
        }

        // Если не найдена сессия, попробуем найти последнюю завершенную сессию пользователя
        if (!$this->testSession) {
            $this->testSession = TestSession::where("user_id", Auth::id())
                ->where("status", "completed")
                ->with([
                    "userAnswers" => function ($query) {
                        $query->orderBy("created_at", "asc");
                    },
                ])
                ->latest("completed_at")
                ->first();
        }

        if (!$this->testSession || $this->testSession->userAnswers->isEmpty()) {
            // Если нет данных, показываем сообщение об отсутствии результатов
            $this->userResults = [];
            $this->userIntellectResults = [];
            $this->domainScores = [];
            $this->topStrengths = [];
            return;
        }

        // Обновляем временные метрики сессии
        $this->testSession->updateTimeMetrics();

        // Устанавливаем данные сессии
        $this->testSessionId = $this->testSession->session_id;
        $this->averageResponseTime =
            $this->testSession->average_response_time ?? 0;
        $this->totalTimeSpent = $this->testSession->total_time_spent ?? 0;
        $this->testDate =
            $this->testSession->completed_at ?? $this->testSession->created_at;
        $this->answersCount = $this->testSession->userAnswers->count();

        $this->calculateResults();
    }
    private function calculateResults()
    {
        $userAnswers = $this->testSession->userAnswers;

        // Get all talents with their domains, ordered by ID for consistency
        /** @var Talent[] $talents */
        $talents = Talent::with("domain")->orderBy("id")->get();

        /** @var Intellect[] $intellects */
        $intellects = Intellect::query()->orderBy("id")->get();

        // Get all answers with their talent relationships
        /** @var Answer[]|Collection $answers */
        $answers = Answer::with(["talent.domain", 'intellect'])->orderBy("id")->get();

        // Define the domains with exact names from database
        $this->localizedDomains = [
            'ru' => [
                "executing" => "ИСПОЛНЕНИЕ",
                "influencing" => "ВЛИЯНИЕ",
                "relationship" => "ОТНОШЕНИЯ",
                "strategic" => "МЫШЛЕНИЕ",
            ],
            'kk' => [
                "executing" => "ОРЫНДАУ",
                "influencing" => "ЫҚПАЛ ЕТУ",
                "relationship" => "ҚАРЫМ-ҚАТЫНАС",
                "strategic" => "СТРАТЕГИЯЛЫҚ ОЙЛАУ",
            ]
        ];

        $this->domainsDescription = [
            'ru' => [
                "executing" => "Вы умеете доводить дела до конца, брать на себя ответственность и организовывать процесс.
                    Ваши таланты из блока Исполнение помогают превращать планы в конкретные результаты и достигать поставленных целей.",
                "influencing" => "Вы умеете брать на себя инициативу, уверенно выражать свои мысли и вдохновлять других на действия.
                    Ваши таланты из блока Влияние помогают мотивировать окружающих, убеждать их и добиваться значимых изменений.",
                "relationship" => "Вы умеете строить доверие, ценить искренние связи и объединять людей вокруг себя.
                    Ваши таланты из блока Отношения помогают укреплять командный дух, создавать атмосферу поддержки и формировать прочные связи.",
                "strategic" => "Вы умеете анализировать информацию, видеть картину целиком и предлагать нестандартные решения.
                    Ваши таланты из блока Мышление помогают находить стратегии, строить прогнозы и двигаться к будущему с ясным видением.",
            ],
            'kk' => [
                "executing" => "Сіз істерді соңына дейін жеткізіп, жауапкершілікті өз мойныңызға алып, процесті ұйымдастыра аласыз.
                    Сіздің Орындау блогындағы таланттарыңыз жоспарларды нақты нәтижеге айналдыруға және қойылған мақсаттарға жетуге көмектеседі.",
                "influencing" => "Сіз бастамашыл болып, ойыңызды нық жеткізіп, өзгелерді әрекетке шабыттандыра аласыз.
                    Сіздің Ықпал ету блогындағы таланттарыңыз айналаңыздағы адамдарды жігерлендіруге, оларды сендіруге және маңызды өзгерістерге қол жеткізуге көмектеседі.",
                "relationship" => "Сіз сенім орнатып, шынайы қарым-қатынастарды бағалап, адамдарды өзіңізге топтастыра аласыз.
                    Сіздің Қарым-қатынас блогындағы таланттарыңыз командалық рухты нығайтуға, қолдау атмосферасын қалыптастыруға және берік байланыстар орнатуға көмектеседі.",
                "strategic" => "Сіз ақпаратты талдап, жалпы көріністі көре аласыз және өзгеше шешімдер ұсына аласыз.
                    Сіздің Ойлау блогындағы таланттарыңыз стратегияларды табуға, болашақты болжауға және айқын көзқараспен алға жылжуға көмектеседі.",
            ]
        ];

        $this->domains = [
            "executing" => "ИСПОЛНЕНИЕ",
            "influencing" => "ВЛИЯНИЕ",
            "relationship" => "ОТНОШЕНИЯ",
            "strategic" => "МЫШЛЕНИЕ",
        ];

        // Initialize domain scores - используем стандартные ключи
        foreach ($this->domains as $key => $domain) {
            $this->domainScores[$key] = 0;
        }

        // Group answers by question_id and sum values (in case of duplicates)
        $questionScores = [];
        foreach ($userAnswers as $userAnswer) {
            $questionId = $userAnswer->question_id;
            if (!isset($questionScores[$questionId])) {
                $questionScores[$questionId] = 0;
            }
            $questionScores[$questionId] += $userAnswer->answer_value;
        }

        // Initialize talent scores
        $talentScores = [];
        foreach ($talents as $talent) {
            $talentScores[$talent->id] = 0;
        }

        $intellectScores = [];
        foreach ($intellects as $intellect) {
            $intellectScores[$intellect->id] = 0;
        }

        // Calculate scores for each talent by grouping answers to each talent
        foreach ($questionScores as $questionId => $score) {
            // Находим ответ (вопрос) по его ID
            $answer = $answers->firstWhere('id', $questionId);
            if ($answer && $answer->talent) {
                // Add the score to the corresponding talent
                $talentScores[$answer->talent->id] += $score;
            }
            elseif ($answer && $answer->intellect) {
                $intellectScores[$answer->intellect->id] += $score;
            }
        }

        // Build results and calculate domain scores
        foreach ($talents as $talent) {
            $score = $talentScores[$talent->id] ?? 0;
            $domainName = $talent->domain ? $talent->domain->name : "executing";

            // Преобразуем название домена из БД в стандартный ключ
            $domainKey = $this->mapDomainNameToKey($domainName);

            $this->userResults[] = [
                "id" => $talent->id,
                "name" => $talent->localizedName,
                "description" => $talent->localizedDescription ?? "",
                "short_description" => $talent->localizedShortDescription ?? "",
                "advice" => $talent->advice ?? "",
                "domain" => $domainKey, // Используем стандартный ключ
                "score" => $score,
                "rank" => 0, // Will be set later
            ];

            // Add talent score to corresponding domain using mapped key
            if (isset($this->domainScores[$domainKey])) {
                $this->domainScores[$domainKey] += $score;
            }
        }

        foreach ($intellects as $intellect) {
            $score = $intellectScores[$intellect->id] ?? 0;

            $this->userIntellectResults[] = [
                "id" => $intellect->id,
                "name" => $intellect->localizedName,
                "description" => $intellect->localizedDescription ?? "",
                "score" => $score,
                "rank" => 0, // Will be set later
            ];
        }

        $this->userResultsCopy = $this->userResults;
        // Sort results by score (descending)
        usort($this->userResults, function ($a, $b) {
            return $b["score"] <=> $a["score"];
        });

        usort($this->userIntellectResults, function ($a, $b) {
            return $b["score"] <=> $a["score"];
        });

        $scorePositions = [];
        foreach ($this->userResultsCopy as $index => $result) {
            $score = $result['score'];
            if (!isset($scorePositions[$score])) {
                $scorePositions[$score] = [];
            }
            $scorePositions[$score][] = $index;
        }

        krsort($scorePositions);

        $currentRank = 1;
        foreach ($scorePositions as $score => $indices) {
            foreach ($indices as $index) {
                $this->userResultsCopy[$index]['rank'] = $currentRank++;
            }
        }

        // Assign ranks
        for ($i = 0; $i < count($this->userResults); $i++) {
            $this->userResults[$i]["rank"] = $i + 1;
        }

        // Get top strengths (up to 5 or whatever is available)
        $this->topStrengths = array_slice(
            $this->userResults,
            0,
            min(5, count($this->userResults))
        );
    }

    public function getFormattedTimeSpentProperty()
    {
        if (!$this->totalTimeSpent || $this->totalTimeSpent <= 0) {
            return "Нет данных";
        }

        $hours = floor($this->totalTimeSpent / 3600);
        $minutes = floor(($this->totalTimeSpent % 3600) / 60);
        $seconds = $this->totalTimeSpent % 60;

        if ($hours > 0) {
            return sprintf("%dч %dм %dс", $hours, $minutes, $seconds);
        } elseif ($minutes > 0) {
            return sprintf("%dм %dс", $minutes, $seconds);
        } else {
            return sprintf("%dс", $seconds);
        }
    }

    public function getTestStatusProperty()
    {
        if (!$this->testSession) {
            return "Демо данные";
        }

        return $this->testSession->status === "completed"
            ? "Завершен"
            : "В процессе";
    }

    public function getPaymentStatusProperty()
    {
        if (!$this->testSession) {
            return null;
        }

        return $this->testSession->payment_status;
    }

    public function getDomainPercentagesProperty()
    {
        $totalScore = array_sum($this->domainScores);
        $percentages = [];

        if ($totalScore > 0) {
            foreach ($this->domainScores as $domain => $score) {
                $percentages[$domain] = round(($score / $totalScore) * 100, 1);
            }
        } else {
            foreach ($this->domainScores as $domain => $score) {
                $percentages[$domain] = 0;
            }
        }

        return $percentages;
    }

    public function getTopSpheres($professions)
    {
        $grouped = $professions->groupBy('sphere_id');

        $spheres = $grouped->map(function ($items, $sphereId) {
            $sphereName = $items->first()['sphere_name'];
            $sphere = Sphere::query()->find($sphereId);

            // average compatibility
            $avgCompatibility = $items->avg('compatibility_percentage');

            return [
                "id" => $sphereId,
                "name" => $sphereName,
                "description" => $sphere->localizedDescription ?? "",
                "is_top" => false,
                "relevance_score" => 999,
                "compatibility_percentage" => round($avgCompatibility, 1),
            ];
        });

        return $spheres->sortByDesc('compatibility_percentage')->values();
    }

    public function getTopProfessions()
    {
        // Создаем массив с очками пользователя по талантам
        $userTalentScores = [];
        foreach ($this->userResults as $result) {
            $userTalentScores[$result["id"]] = $result["score"];
        }

        $userIntellectScore = [];
        foreach ($this->userIntellectResults as $result) {
            $userIntellectScore[$result["id"]] = $result["score"];
        }

        // Получаем все профессии с их талантами
        /** @var Profession[] $allProfessions */
        $allProfessions = Profession::with([
            "talents",
            "sphere",
            'intellects'
        ])->get();
        $professionsData = collect();

        foreach ($allProfessions as $profession) {
            $compatibilityPercentage = 0;
            $compatibilityIntellectPercentage = 0;

            if ($profession->talents && $profession->talents->count() > 0) {
                $totalWeightedScore = 0;

                foreach ($profession->talents as $talent) {
                    $totalWeightedScore += $userTalentScores[$talent->id] ?? 0;
                }

                $compatibilityPercentage = $totalWeightedScore;
            }

            if ($profession->intellects && $profession->intellects->count() > 0) {
                $totalWeightedScore = 0;

                foreach ($profession->intellects as $intellect) {
                    $totalWeightedScore += $userIntellectScore[$intellect->id] ?? 0;
                }

                $compatibilityIntellectPercentage = $totalWeightedScore;
            }

            $totalCompatabilityPercentage = ($compatibilityPercentage + $compatibilityIntellectPercentage) * 100 / 250;

            $professionRating = $profession->rating;

            /** @var User $user */
            $user = Auth::user();
            $genderRating = ($user->gender === 'male')
                ? $profession->man
                : $profession->woman;

            $totalCompatabilityPercentage = $totalCompatabilityPercentage * ($professionRating/100) * ($genderRating/100);

            $professionsData->push([
                "id" => $profession->id,
                "name" => $profession->localizedName,
                "description" => $profession->localizedDescription ?? "",
                "sphere_id" => $profession->sphere
                    ? $profession->sphere->id
                    : null,
                "sphere_name" => $profession->sphere
                    ? $profession->sphere->name
                    : "",
                "is_top" => false, // Будет установлено после сортировки
                "relevance_score" => 999,
                "compatibility_percentage" => $totalCompatabilityPercentage,
            ]);
        }

        // Сортируем: сначала по проценту совместимости (убывание), затем по имени (по возрастанию)
        $sortedProfessions = $professionsData
            ->sortByDesc("compatibility_percentage")
            ->sortBy("name")
            ->sortByDesc("compatibility_percentage") // Повторная сортировка по проценту для финального порядка
            ->values();

        // Помечаем первые 8 как топовые
        $topProfessions = $sortedProfessions->map(function (
            $profession,
            $index
        ) {
            $profession["is_top"] = $index < 8; // Только первые 8 профессий топовые
            return $profession;
        });

        return $topProfessions;
    }

    /**
     * Проверяет, доступна ли вкладка "Топ сферы" для текущего тарифа
     */
    public function getCanViewSpheresTabProperty()
    {
        if (!$this->testSession) {
            return false; // По умолчанию не показываем если нет сессии
        }

        // Проверяем оплачена ли сессия
        if (!$this->testSession->isPaid()) {
            return false;
        }

        // Проверяем тарифный план
        $allowedPlans = ["talents_spheres", "talents_spheres_professions"];
        return in_array($this->testSession->selected_plan, $allowedPlans);
    }

    /**
     * Проверяет, доступна ли вкладка "Топ профессии" для текущего тарифа
     */
    public function getCanViewProfessionsTabProperty()
    {
        if (!$this->testSession) {
            return false; // По умолчанию не показываем если нет сессии
        }

        // Проверяем оплачена ли сессия
        if (!$this->testSession->isPaid()) {
            return false;
        }

        // Проверяем тарифный план - только полный план
        return $this->testSession->selected_plan ===
            "talents_spheres_professions";
    }

    /**
     * Проверяет, является ли тариф полным (показывать подробные описания талантов)
     */
    public function getIsFullPlanProperty()
    {
        if (!$this->testSession) {
            return false;
        }

        // Проверяем оплачена ли сессия
        if (!$this->testSession->isPaid()) {
            return false;
        }

        // Проверяем тарифный план - только полный план
        return $this->testSession->selected_plan ===
            "talents_spheres_professions";
    }

    /**
     * Получает советы для конкретного таланта
     */
    public function getTalentAdvice($talentName)
    {
        // Сначала ищем талант в базе данных
        $talent = Talent::where("name", $talentName)->first();

        if ($talent && !empty($talent->advice)) {
            return $talent->advice;
        }

        // Если в базе нет советов, используем дефолтные
        $adviceMap = [
            "Achiever" => '
                <div class="space-y-3">
                    <p><strong>1. Устанавливайте ежедневные цели:</strong> Составляйте список задач на каждый день и отмечайте выполненные. Это поможет поддерживать чувство достижения.</p>
                    <p><strong>2. Измеряйте прогресс:</strong> Ведите учет своих достижений. Записывайте все завершенные проекты и выполненные задачи.</p>
                    <p><strong>3. Работайте с единомышленниками:</strong> Окружите себя людьми, которые также стремятся к результатам и могут поддержать ваш драйв.</p>
                    <p><strong>4. Разбивайте большие цели:</strong> Большие проекты делите на более мелкие, измеримые этапы для постоянного ощущения прогресса.</p>
                    <p><strong>5. Отдыхайте осознанно:</strong> Планируйте время для восстановления, но делайте это структурированно, чтобы не терять импульс.</p>
                </div>
            ',
            "Belief" => '
                <div class="space-y-3">
                    <p><strong>1. Определите свои ценности:</strong> Четко сформулируйте свои основные убеждения и принципы. Записывайте их и регулярно пересматривайте.</p>
                    <p><strong>2. Выбирайте работу по ценностям:</strong> Ищите возможности и проекты, которые соответствуют вашим глубинным убеждениям.</p>
                    <p><strong>3. Будьте наставником:</strong> Делитесь своими ценностями с другими, помогайте им найти свой путь, основанный на принципах.</p>
                    <p><strong>4. Не идите на компромиссы:</strong> Избегайте ситуаций, которые противоречат вашим основным убеждениям, даже если это кажется выгодным.</p>
                    <p><strong>5. Найдите свою миссию:</strong> Ищите способы внести вклад в то, что действительно важно для вас и общества.</p>
                </div>
            ',
            "Focus" => '
                <div class="space-y-3">
                    <p><strong>1. Устанавливайте приоритеты:</strong> Каждую неделю определяйте 3-5 самых важных задач и концентрируйтесь на них.</p>
                    <p><strong>2. Избегайте многозадачности:</strong> Работайте над одной задачей за раз, полностью погружаясь в процесс.</p>
                    <p><strong>3. Создайте ритуалы:</strong> Разработайте рутины, которые помогают вам быстро входить в состояние концентрации.</p>
                    <p><strong>4. Говорите "нет":</strong> Учитесь отказываться от отвлекающих возможностей, которые не соответствуют вашим целям.</p>
                    <p><strong>5. Планируйте время глубокой работы:</strong> Выделяйте специальные блоки времени для сосредоточенной работы без прерываний.</p>
                </div>
            ',
            "Responsibility" => '
                <div class="space-y-3">
                    <p><strong>1. Берите на себя обязательства:</strong> Добровольно принимайте ответственность за важные проекты и результаты.</p>
                    <p><strong>2. Держите слово:</strong> Всегда выполняйте обещания, даже если это требует дополнительных усилий.</p>
                    <p><strong>3. Планируйте с запасом:</strong> Учитывайте возможные задержки и проблемы при планировании проектов.</p>
                    <p><strong>4. Развивайте других:</strong> Помогайте коллегам развивать чувство ответственности, подавая личный пример.</p>
                    <p><strong>5. Документируйте процессы:</strong> Создавайте четкие процедуры и инструкции, чтобы обеспечить качественное выполнение задач.</p>
                </div>
            ',
            "Restorative" => '
                <div class="space-y-3">
                    <p><strong>1. Ищите проблемы:</strong> Активно выявляйте проблемы и неэффективности в рабочих процессах.</p>
                    <p><strong>2. Анализируйте причины:</strong> Не просто устраняйте симптомы, но докапывайтесь до корня проблем.</p>
                    <p><strong>3. Развивайте диагностические навыки:</strong> Изучайте методы анализа и решения проблем в своей области.</p>
                    <p><strong>4. Создавайте системы предотвращения:</strong> Разрабатывайте процессы, которые предотвращают повторение проблем.</p>
                    <p><strong>5. Делитесь опытом:</strong> Обучайте других тому, как выявлять и решать проблемы эффективно.</p>
                </div>
            ',
            "Communication" => '
                <div class="space-y-3">
                    <p><strong>1. Практикуйте активное слушание:</strong> Развивайте способность внимательно слушать и понимать других людей.</p>
                    <p><strong>2. Адаптируйте стиль общения:</strong> Подстраивайте манеру речи под аудиторию и ситуацию.</p>
                    <p><strong>3. Рассказывайте истории:</strong> Используйте примеры и истории для более эффективной передачи идей.</p>
                    <p><strong>4. Развивайте невербальное общение:</strong> Обращайте внимание на язык тела и тон голоса.</p>
                    <p><strong>5. Просите обратную связь:</strong> Регулярно узнавайте, как другие воспринимают ваше общение.</p>
                </div>
            ',
            "Empathy" => '
                <div class="space-y-3">
                    <p><strong>1. Слушайте эмоции:</strong> Обращайте внимание не только на слова, но и на эмоциональное состояние собеседника.</p>
                    <p><strong>2. Задавайте открытые вопросы:</strong> Помогайте людям выразить свои чувства и переживания.</p>
                    <p><strong>3. Практикуйте понимание:</strong> Старайтесь увидеть ситуацию глазами другого человека.</p>
                    <p><strong>4. Создавайте безопасное пространство:</strong> Формируйте среду, где люди чувствуют себя комфортно.</p>
                    <p><strong>5. Помогайте в трудные моменты:</strong> Используйте свою способность понимать для поддержки окружающих.</p>
                </div>
            ',
            "Strategic" => '
                <div class="space-y-3">
                    <p><strong>1. Анализируйте варианты:</strong> Всегда рассматривайте несколько альтернативных путей достижения цели.</p>
                    <p><strong>2. Думайте наперед:</strong> Регулярно анализируйте долгосрочные последствия решений.</p>
                    <p><strong>3. Создавайте сценарии:</strong> Разрабатывайте планы "что если" для различных ситуаций.</p>
                    <p><strong>4. Изучайте паттерны:</strong> Ищите повторяющиеся закономерности в данных и ситуациях.</p>
                    <p><strong>5. Делитесь видением:</strong> Помогайте другим понять стратегическую картину и логику решений.</p>
                </div>
            ',
            "Learner" => '
                <div class="space-y-3">
                    <p><strong>1. Планируйте обучение:</strong> Выделяйте время каждый день для изучения чего-то нового.</p>
                    <p><strong>2. Ведите журнал знаний:</strong> Записывайте новые концепции и идеи, которые изучаете.</p>
                    <p><strong>3. Ищите вызовы:</strong> Выбирайте проекты, которые требуют освоения новых навыков.</p>
                    <p><strong>4. Учите других:</strong> Делитесь полученными знаниями - это укрепляет ваше понимание.</p>
                    <p><strong>5. Экспериментируйте:</strong> Применяйте новые знания на практике, чтобы закрепить их.</p>
                </div>
            ',
            "Analytical" => '
                <div class="space-y-3">
                    <p><strong>1. Собирайте данные:</strong> Всегда ищите факты и доказательства перед принятием решений.</p>
                    <p><strong>2. Задавайте "почему":</strong> Не принимайте информацию на веру, исследуйте причины и связи.</p>
                    <p><strong>3. Используйте логические модели:</strong> Применяйте структурированные подходы к анализу проблем.</p>
                    <p><strong>4. Проверяйте гипотезы:</strong> Формулируйте предположения и тестируйте их систематически.</p>
                    <p><strong>5. Документируйте выводы:</strong> Ведите записи своих аналитических процессов и результатов.</p>
                </div>
            ',
        ];

        return $adviceMap[$talentName] ??
            '
            <div class="space-y-3">
                <p><strong>1. Изучите свой талант:</strong> Углубите понимание того, как этот талант проявляется в вашей жизни и работе.</p>
                <p><strong>2. Найдите применение:</strong> Ищите возможности использовать этот талант в различных ситуациях.</p>
                <p><strong>3. Развивайте сознательно:</strong> Практикуйте и совершенствуйте проявления этого таланта.</p>
                <p><strong>4. Комбинируйте с другими:</strong> Находите способы сочетать этот талант с другими вашими сильными сторонами.</p>
                <p><strong>5. Помогайте другим:</strong> Используйте этот талант, чтобы поддерживать и развивать окружающих.</p>
            </div>
        ';
    }

    public function exportTalentDescriptionsPdf()
    {
        // Вместо прямого скачивания, просто флешим флаг для JS
        session()->flash('download_pdf', true);
    }

    /**
     * Показывает модальное окно для обновления тарифа
     */
    public function showUpgradeModal()
    {
        // Можно добавить логику для показа модального окна обновления тарифа
        // Или перенаправить на страницу тарифов
        session()->flash('show_upgrade_modal', true);
        session()->flash('upgrade_message', 'Для скачивания этого раздела необходимо обновить тарифный план.');
    }

    /**
     * Проверяет, может ли пользователь скачать PDF для конкретной вкладки
     */
    public function canDownloadTab($tab)
    {
        switch ($tab) {
            case 'talents':
                return true; // Таланты доступны всегда
            case 'spheres':
                return $this->canViewSpheresTab;
            case 'professions':
                return $this->canViewProfessionsTab;
            default:
                return false;
        }
    }

    /**
     * Сопоставляет название домена из БД со стандартным ключом
     */
    private function mapDomainNameToKey($domainName)
    {
        // Сопоставление различных вариантов названий доменов
        $mapping = [
            'executing' => 'executing',
            'EXECUTING' => 'executing',
            'Executing' => 'executing',
            'ИСПОЛНЕНИЕ' => 'executing',
            'исполнение' => 'executing',
            'Исполнение' => 'executing',

            'influencing' => 'influencing',
            'INFLUENCING' => 'influencing',
            'Influencing' => 'influencing',
            'ВЛИЯНИЕ' => 'influencing',
            'влияние' => 'influencing',
            'Влияние' => 'influencing',

            'relationship' => 'relationship',
            'RELATIONSHIP' => 'relationship',
            'Relationship' => 'relationship',
            'RELATIONSHIP BUILDING' => 'relationship',
            'relationship building' => 'relationship',
            'Relationship Building' => 'relationship',
            'ОТНОШЕНИЯ' => 'relationship',
            'отношения' => 'relationship',
            'Отношения' => 'relationship',

            'strategic' => 'strategic',
            'STRATEGIC' => 'strategic',
            'Strategic' => 'strategic',
            'STRATEGIC THINKING' => 'strategic',
            'strategic thinking' => 'strategic',
            'Strategic Thinking' => 'strategic',
            'МЫШЛЕНИЕ' => 'strategic',
            'мышление' => 'strategic',
            'Мышление' => 'strategic',
        ];

        return $mapping[$domainName] ?? 'executing'; // По умолчанию executing
    }

    public function render()
    {
        $professions = $this->getTopProfessions();
        $topProfessionIds = $professions
            ->where("is_top", true)
            ->pluck("id")
            ->toArray();

        $spheres = $this->getTopSpheres($professions);
        $topSphereIds = $spheres->where("is_top", true)->pluck("id")->toArray();

        return view("livewire.pages.talent-test-results", [
            "userResults" => $this->userResults,
            "domains" => $this->domains,
            "domainScores" => $this->domainScores,
            "topStrengths" => $this->topStrengths,
            "domainPercentages" => $this->getDomainPercentagesProperty(),
            "topSpheres" => $spheres,
            "topSphereIds" => $topSphereIds,
            "topProfessions" => $professions,
            "topProfessionIds" => $topProfessionIds,
        ]);
    }
}
