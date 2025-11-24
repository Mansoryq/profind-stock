<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\EntSubject;
use App\Models\University;
use App\Models\Speciality;
use Illuminate\Support\Collection;

class GrantAnalysis extends Component
{
    public $selectedSubjects = [];
    public $availableSubjects = [];
    public $matchingSpecialities = [];
    public $minScore = 70;
    public $analysisPerformed = false;

    public function mount()
    {
        // Создаем тестовые данные для предметов ЕНТ на основе скриншота
        $this->availableSubjects = collect([
            (object)['id' => 1, 'name' => 'Математика', 'code' => 'MATH'],
            (object)['id' => 2, 'name' => 'Физика', 'code' => 'PHYS'],
            (object)['id' => 3, 'name' => 'Химия', 'code' => 'CHEM'],
            (object)['id' => 4, 'name' => 'Биология', 'code' => 'BIO'],
            (object)['id' => 5, 'name' => 'География', 'code' => 'GEO'],
            (object)['id' => 6, 'name' => 'История Казахстана', 'code' => 'HIST_KZ'],
            (object)['id' => 7, 'name' => 'Всемирная история', 'code' => 'HIST_WORLD'],
            (object)['id' => 8, 'name' => 'Литература', 'code' => 'LIT'],
            (object)['id' => 9, 'name' => 'Информатика', 'code' => 'INFO'],
            (object)['id' => 10, 'name' => 'Английский язык', 'code' => 'ENG'],
            (object)['id' => 11, 'name' => 'Казахский язык', 'code' => 'KAZ'],
            (object)['id' => 12, 'name' => 'Русский язык', 'code' => 'RUS'],
            // Добавляем предметы из скриншота
            (object)['id' => 13, 'name' => 'Ауыл квотасы', 'code' => 'RURAL'],
            (object)['id' => 14, 'name' => 'Көпбалалы отбасы', 'code' => 'LARGE_FAM'],
            (object)['id' => 15, 'name' => 'Толық емес отбасы', 'code' => 'SINGLE_PAR'],
            (object)['id' => 16, 'name' => 'Серпін', 'code' => 'SERPIN'],
            (object)['id' => 17, 'name' => 'Мүгедектігі бар адамдар', 'code' => 'DISABLED'],
            (object)['id' => 18, 'name' => 'Мүгедектігі бар отбасынан', 'code' => 'DISABLED_FAM'],
            (object)['id' => 19, 'name' => 'Жетім балалар', 'code' => 'ORPHAN'],
            (object)['id' => 20, 'name' => 'Дерексіз дұрыс енгіздім', 'code' => 'CORRECT_DATA'],
        ]);
    }

    public function updatedSelectedSubjects()
    {
        $this->analysisPerformed = false;
        $this->matchingSpecialities = [];
    }

    public function analyzeGrants()
    {
        if (empty($this->selectedSubjects)) {
            session()->flash('error', 'Пожалуйста, выберите хотя бы один предмет');
            return;
        }

        // Тестовые данные для университетов и специальностей
        $testData = $this->getTestData();

        // Фильтруем по выбранным предметам
        $filtered = collect($testData)->filter(function($university) {
            return collect($university['specialities'])->filter(function($speciality) {
                $requiredSubjects = array_filter([
                    $speciality['subject_1'] ?? null,
                    $speciality['subject_2'] ?? null,
                    $speciality['subject_3'] ?? null,
                    $speciality['subject_4'] ?? null,
                    $speciality['subject_5'] ?? null,
                ]);

                // Проверяем, совпадает ли хотя бы один предмет
                foreach ($requiredSubjects as $subject) {
                    if (in_array($subject, $this->selectedSubjects)) {
                        return true;
                    }
                }
                return false;
            })->isNotEmpty();
        });

        // Применяем дополнительные фильтры
        // Фильтруем специальности внутри университетов
        $this->matchingSpecialities = $filtered->mapWithKeys(function($university) {
            $filteredSpecialities = collect($university['specialities'])->filter(function($speciality) {
                $requiredSubjects = array_filter([
                    $speciality['subject_1'] ?? null,
                    $speciality['subject_2'] ?? null,
                    $speciality['subject_3'] ?? null,
                    $speciality['subject_4'] ?? null,
                    $speciality['subject_5'] ?? null,
                ]);

                // Проверяем совпадение предметов
                foreach ($requiredSubjects as $subject) {
                    if (in_array($subject, $this->selectedSubjects)) {
                        return true;
                    }
                }
                return false;
            })->filter(function($speciality) {
                // Применяем фильтры
                if ($speciality['passing_score'] < $this->minScore) {
                    return false;
                }
                return true;
            })->sortByDesc('grant_count')->values();

            return [$university['name'] => $filteredSpecialities];
        })->filter(function($specialities) {
            return $specialities->isNotEmpty();
        });

        $this->analysisPerformed = true;
    }

    public function resetFilters()
    {
        $this->selectedSubjects = [];
        $this->minScore = 70;
        $this->matchingSpecialities = [];
        $this->analysisPerformed = false;
    }

    public function render()
    {
        $cities = collect(['Алматы', 'Астана', 'Шымкент', 'Караганда', 'Актобе', 'Тараз']);
        $commonCombinations = [
            'Медицинское направление' => ['Математика', 'Биология', 'Химия'],
            'Техническое направление' => ['Математика', 'Физика', 'Информатика'],
            'IT направление' => ['Математика', 'Физика', 'Информатика'],
            'Экономическое направление' => ['Математика', 'География', 'Всемирная история'],
            'Юридическое направление' => ['История Казахстана', 'Всемирная история', 'География'],
            'Педагогическое направление' => ['История Казахстана', 'География', 'Литература'],
            'Гуманитарное направление' => ['История Казахстана', 'Литература', 'География'],
            'Социальная поддержка' => ['Ауыл квотасы', 'Көпбалалы отбасы', 'Толық емес отбасы'],
            'Льготные категории' => ['Серпін', 'Мүгедектігі бар адамдар', 'Жетім балалар'],
        ];

//        return view('livewire.grant-analysis', compact('cities', 'commonCombinations'));
        return view('livewire.grant-analysis-in-process');
    }

    private function getTestData()
    {
        return [
            [
                'name' => 'Казахский национальный университет имени аль-Фараби',
                'city' => 'Алматы',
                'type' => 'государственный',
                'specialities' => [
                    [
                        'name' => 'Информационные системы',
                        'faculty' => 'Факультет информационных технологий',
                        'code' => '6B06101',
                        'grant_count' => 150,
                        'passing_score' => 118,
                        'language' => 'казахский',
                        'degree_type' => 'бакалавр',
                        'duration_years' => 4,
                        'subject_1' => 'Математика',
                        'subject_2' => 'Физика',
                        'subject_3' => 'Информатика',
                        'description' => 'Подготовка специалистов в области разработки информационных систем'
                    ],
                    [
                        'name' => 'Медицина',
                        'faculty' => 'Медицинский факультет',
                        'code' => '6B10101',
                        'grant_count' => 200,
                        'passing_score' => 125,
                        'language' => 'казахский',
                        'degree_type' => 'бакалавр',
                        'duration_years' => 6,
                        'subject_1' => 'Математика',
                        'subject_2' => 'Биология',
                        'subject_3' => 'Химия',
                        'description' => 'Подготовка врачей общей практики'
                    ],
                    [
                        'name' => 'Экономика',
                        'faculty' => 'Экономический факультет',
                        'code' => '6B04101',
                        'grant_count' => 100,
                        'passing_score' => 110,
                        'language' => 'казахский',
                        'degree_type' => 'бакалавр',
                        'duration_years' => 4,
                        'subject_1' => 'Математика',
                        'subject_2' => 'География',
                        'subject_3' => 'Всемирная история',
                        'description' => 'Подготовка экономистов-аналитиков'
                    ]
                ]
            ],
            [
                'name' => 'Казахский национальный технический университет имени К.И. Сатпаева',
                'city' => 'Алматы',
                'type' => 'государственный',
                'specialities' => [
                    [
                        'name' => 'Нефтегазовое дело',
                        'faculty' => 'Факультет нефти и газа',
                        'code' => '6B07101',
                        'grant_count' => 120,
                        'passing_score' => 115,
                        'language' => 'казахский',
                        'degree_type' => 'бакалавр',
                        'duration_years' => 4,
                        'subject_1' => 'Математика',
                        'subject_2' => 'Физика',
                        'subject_3' => 'Химия',
                        'description' => 'Подготовка инженеров нефтегазовой отрасли'
                    ],
                    [
                        'name' => 'Горное дело',
                        'faculty' => 'Горно-металлургический факультет',
                        'code' => '6B07201',
                        'grant_count' => 80,
                        'passing_score' => 112,
                        'language' => 'казахский',
                        'degree_type' => 'бакалавр',
                        'duration_years' => 4,
                        'subject_1' => 'Математика',
                        'subject_2' => 'Физика',
                        'subject_3' => 'География',
                        'description' => 'Подготовка горных инженеров'
                    ]
                ]
            ],
            [
                'name' => 'Назарбаев Университет',
                'city' => 'Астана',
                'type' => 'частный',
                'specialities' => [
                    [
                        'name' => 'Computer Science',
                        'faculty' => 'School of Engineering and Digital Sciences',
                        'code' => '6B06102',
                        'grant_count' => 50,
                        'passing_score' => 130,
                        'language' => 'английский',
                        'degree_type' => 'бакалавр',
                        'duration_years' => 4,
                        'subject_1' => 'Математика',
                        'subject_2' => 'Физика',
                        'subject_3' => 'Информатика',
                        'description' => 'Подготовка IT-специалистов международного уровня'
                    ],
                    [
                        'name' => 'Biomedical Engineering',
                        'faculty' => 'School of Engineering and Digital Sciences',
                        'code' => '6B07103',
                        'grant_count' => 30,
                        'passing_score' => 128,
                        'language' => 'английский',
                        'degree_type' => 'бакалавр',
                        'duration_years' => 4,
                        'subject_1' => 'Математика',
                        'subject_2' => 'Биология',
                        'subject_3' => 'Физика',
                        'description' => 'Биомедицинская инженерия'
                    ]
                ]
            ],
            [
                'name' => 'Евразийский национальный университет имени Л.Н. Гумилева',
                'city' => 'Астана',
                'type' => 'государственный',
                'specialities' => [
                    [
                        'name' => 'Международные отношения',
                        'faculty' => 'Факультет международных отношений',
                        'code' => '6B03101',
                        'grant_count' => 70,
                        'passing_score' => 115,
                        'language' => 'казахский',
                        'degree_type' => 'бакалавр',
                        'duration_years' => 4,
                        'subject_1' => 'История Казахстана',
                        'subject_2' => 'Всемирная история',
                        'subject_3' => 'География',
                        'description' => 'Подготовка специалистов в области международных отношений'
                    ],
                    [
                        'name' => 'Педагогика и психология',
                        'faculty' => 'Педагогический факультет',
                        'code' => '6B01101',
                        'grant_count' => 90,
                        'passing_score' => 105,
                        'language' => 'казахский',
                        'degree_type' => 'бакалавр',
                        'duration_years' => 4,
                        'subject_1' => 'История Казахстана',
                        'subject_2' => 'География',
                        'subject_3' => 'Литература',
                        'description' => 'Подготовка учителей и педагогов-психологов'
                    ]
                ]
            ],
            [
                'name' => 'Южно-Казахстанский государственный университет имени М. Ауэзова',
                'city' => 'Шымкент',
                'type' => 'государственный',
                'specialities' => [
                    [
                        'name' => 'Агрономия',
                        'faculty' => 'Аграрный факультет',
                        'code' => '6B08101',
                        'grant_count' => 60,
                        'passing_score' => 100,
                        'language' => 'казахский',
                        'degree_type' => 'бакалавр',
                        'duration_years' => 4,
                        'subject_1' => 'Математика',
                        'subject_2' => 'Биология',
                        'subject_3' => 'Химия',
                        'description' => 'Подготовка агрономов'
                    ],
                    [
                        'name' => 'Ветеринария',
                        'faculty' => 'Ветеринарный факультет',
                        'code' => '6B08201',
                        'grant_count' => 40,
                        'passing_score' => 108,
                        'language' => 'казахский',
                        'degree_type' => 'бакалавр',
                        'duration_years' => 5,
                        'subject_1' => 'Математика',
                        'subject_2' => 'Биология',
                        'subject_3' => 'Химия',
                        'description' => 'Подготовка ветеринарных врачей'
                    ]
                ]
            ]
        ];
    }
}
