<?php

namespace Database\Seeders;

use App\Models\Intellect;
use App\Models\Profession;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfessionIntellectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rawData = [
            [
                'profession' => 'Инженер-электрик',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Инженер-электрик',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Инженер-механик',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Инженер-механик',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Инженер-строитель',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Инженер-строитель',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Инженер-химик',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Инженер-химик',
                'intellect' => 'Натуралистический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Инженер по автоматизации',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Инженер по автоматизации',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Робототехник',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Робототехник',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Инженер-технолог',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Инженер-технолог',
                'intellect' => 'Натуралистический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Аэрокосмический инженер',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Аэрокосмический инженер',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Инженер-энергетик',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Инженер-энергетик',
                'intellect' => 'Натуралистический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Инженер-конструктор',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Инженер-конструктор',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Материаловед',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Материаловед',
                'intellect' => 'Натуралистический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Нанотехнолог',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Нанотехнолог',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Машиностроитель',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Машиностроитель',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Телекоммуникационный инженер',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Телекоммуникационный инженер',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Финансовый аналитик',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Финансовый аналитик',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Экономист',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Экономист',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Бухгалтер',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Бухгалтер',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Аудитор',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Аудитор',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Банкир',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Банкир',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Инвестиционный менеджер',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Инвестиционный менеджер',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Риск-менеджер',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Риск-менеджер',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Менеджер по продажам',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Менеджер по продажам',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Управляющий проектами (Project Manager)',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Управляющий проектами (Project Manager)',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Консультант по бизнес-стратегии',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Консультант по бизнес-стратегии',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Специалист по налогам',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по налогам',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Руководитель компании (CEO)',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Руководитель компании (CEO)',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Предприниматель',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Предприниматель',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Аналитик данных (Data Analyst) в бизнесе',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Аналитик данных (Data Analyst) в бизнесе',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Специалист по финансовому планированию',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по финансовому планированию',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Туроператор',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Туроператор',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Турагент',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Турагент',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Экскурсовод',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Экскурсовод',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Гид-переводчик',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Гид-переводчик',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Организатор мероприятий (Event Manager)',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Организатор мероприятий (Event Manager)',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Руководитель туристических групп',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Руководитель туристических групп',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Администратор гостиницы',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Администратор гостиницы',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Менеджер по бронированию',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Администратор гостиницы',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Специалист по работе с VIP-клиентами',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по работе с VIP-клиентами',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Управляющий гостиницей',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Управляющий гостиницей',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Специалист по авиабилетам',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по авиабилетам',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Разработчик туристических маршрутов',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Разработчик туристических маршрутов',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Специалист по экотуризму',
                'intellect' => 'Натуралистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по экотуризму',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Инструктор по активным видам туризма',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Инструктор по активным видам туризма',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Культуролог (в сфере туризма)',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Культуролог (в сфере туризма)',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Юрист',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Юрист',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Адвокат',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Адвокат',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Нотариус',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Нотариус',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Судья',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Судья',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Прокурор',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Прокурор',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Следователь',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Следователь',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Эксперт-криминалист',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Эксперт-криминалист',
                'intellect' => 'Натуралистический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Специалист по трудовому праву',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по трудовому праву',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Специалист по международному праву',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по международному праву',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Специалист по интеллектуальной собственности',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по интеллектуальной собственности',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Медиационный юрист',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Медиационный юрист',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Консультант по корпоративному праву',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Консультант по корпоративному праву',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Специалист по уголовному праву',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по уголовному праву',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Врач-терапевт',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Врач-терапевт',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Хирург',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Хирург',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Стоматолог',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Стоматолог',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Педиатр',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Педиатр',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Кардиолог',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Кардиолог',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Невролог',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Невролог',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Офтальмолог',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Офтальмолог',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Ортопед',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Ортопед',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Психиатр',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Психиатр',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Медсестра/медбрат',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Медсестра/медбрат',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Лаборант-аналитик',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Лаборант-аналитик',
                'intellect' => 'Натуралистический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Фармацевт',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Фармацевт',
                'intellect' => 'Натуралистический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Диетолог',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Диетолог',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Физиотерапевт',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Физиотерапевт',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Ветеринар',
                'intellect' => 'Натуралистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Ветеринар',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Гинеколог',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Гинеколог',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Дерматолог',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Дерматолог',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Социолог',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Социолог',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Политолог',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Политолог',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Антрополог',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Антрополог',
                'intellect' => 'Натуралистический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Психолог',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Психолог',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Историк',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Историк',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Культуролог',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Культуролог',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Географ',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Географ',
                'intellect' => 'Натуралистический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Демограф',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Демограф',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Этнограф',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Этнограф',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Эколог',
                'intellect' => 'Натуралистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Эколог',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Исследователь общественного мнения',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Исследователь общественного мнения',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Урбанист',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Урбанист',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Международные отношения',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Международные отношения',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Агроном',
                'intellect' => 'Натуралистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Агроном',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Зоотехник',
                'intellect' => 'Натуралистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Зоотехник',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Ветеринар',
                'intellect' => 'Натуралистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Ветеринар',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Фермер',
                'intellect' => 'Натуралистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Фермер',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Селекционер',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Селекционер',
                'intellect' => 'Натуралистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Биотехнолог в сельском хозяйстве',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Биотехнолог в сельском хозяйстве',
                'intellect' => 'Натуралистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Агроинженер',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Агроинженер',
                'intellect' => 'Натуралистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Специалист по ирригации',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по ирригации',
                'intellect' => 'Натуралистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Специалист по защите растений',
                'intellect' => 'Натуралистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по защите растений',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Техник-агроном',
                'intellect' => 'Натуралистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Техник-агроном',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Рыбовод',
                'intellect' => 'Натуралистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Рыбовод',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Специалист по переработке сельскохозяйственной продукции',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по переработке сельскохозяйственной продукции',
                'intellect' => 'Натуралистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Пчеловод',
                'intellect' => 'Натуралистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Пчеловод',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Инспектор по качеству сельскохозяйственной продукции',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Инспектор по качеству сельскохозяйственной продукции',
                'intellect' => 'Натуралистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Дизайнер одежды',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Дизайнер одежды',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Стилист',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Стилист',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Технолог швейного производства',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Технолог швейного производства',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Визажист',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Визажист',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Парикмахер',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Парикмахер',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Косметолог',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Косметолог',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Фэшн-иллюстратор',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Фэшн-иллюстратор',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Художник по костюмам (в кино, театре)',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Художник по костюмам (в кино, театре)',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Модель',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Модель',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Специалист по текстилю',
                'intellect' => 'Натуралистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по текстилю',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Менеджер модного бренда',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Менеджер модного бренда',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Продавец-консультант в люксовых магазинах',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Продавец-консультант в люксовых магазинах',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Фэшн-аналитик',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Фэшн-аналитик',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Директор модных показов',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Директор модных показов',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Ювелир',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Ювелир',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Учитель (школьный)',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Учитель (школьный)',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Преподаватель вуза',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Преподаватель вуза',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Методист',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Методист',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Воспитатель детского сада',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Воспитатель детского сада',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Логопед',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Логопед',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Коуч по личностному развитию',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Коуч по личностному развитию',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Руководитель образовательных программ',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Руководитель образовательных программ',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Специалист по разработке учебных материалов',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по разработке учебных материалов',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Администратор образовательного учреждения',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Администратор образовательного учреждения',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Исследователь в области педагогики',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Исследователь в области педагогики',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Специалист по дошкольному образованию',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по дошкольному образованию',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Карьерный консультант',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Карьерный консультант',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Профориентолог',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Профориентолог',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Педагог-психолог',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Педагог-психолог',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Программист (Frontend, Backend, Fullstack)',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Программист (Frontend, Backend, Fullstack)',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Разработчик мобильных приложений',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Разработчик мобильных приложений',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Специалист по кибербезопасности',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по кибербезопасности',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Аналитик данных (Data Analyst)',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Аналитик данных (Data Analyst)',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Инженер данных (Data Engineer)',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Инженер данных (Data Engineer)',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Разработчик игр (Game Developer)',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Разработчик игр (Game Developer)',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Тестировщик ПО (QA-инженер)',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Тестировщик ПО (QA-инженер)',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Архитектор программного обеспечения',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Архитектор программного обеспечения',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Инженер по машинному обучению (ML Engineer)',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Инженер по машинному обучению (ML Engineer)',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'UX/UI дизайнер',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'UX/UI дизайнер',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Веб-разработчик',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Веб-разработчик',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Специалист по искусственному интеллекту (AI Specialist)',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по искусственному интеллекту (AI Specialist)',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Продуктовый менеджер (Product Manager)',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Продуктовый менеджер (Product Manager)',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'IT-консультант',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'IT-консультант',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Специалист по автоматизации процессов (RPA Developer)',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по автоматизации процессов (RPA Developer)',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Инженер-энергетик',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Инженер-энергетик',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Геофизик',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Геофизик',
                'intellect' => 'Натуралистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Инженер по разработке месторождений',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Инженер по разработке месторождений',
                'intellect' => 'Натуралистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Специалист по бурению скважин',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по бурению скважин',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Специалист по альтернативной энергетике',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по альтернативной энергетике',
                'intellect' => 'Натуралистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Оператор нефтяных и газовых установок',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Оператор нефтяных и газовых установок',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Механик по обслуживанию горного оборудования',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Механик по обслуживанию горного оборудования',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Машинист буровых установок',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Машинист буровых установок',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Шахтёр',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Шахтёр',
                'intellect' => 'Натуралистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Специалист по переработке нефти и газа',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по переработке нефти и газа',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Эколог (в энергетике и добыче)',
                'intellect' => 'Натуралистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Эколог (в энергетике и добыче)',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Специалист по автоматизации энергетических систем',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по автоматизации энергетических систем',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Руководитель шахты или месторождения',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Руководитель шахты или месторождения',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Инженер-гидротехник',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Инженер-гидротехник',
                'intellect' => 'Натуралистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Учёный-исследователь',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Учёный-исследователь',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Физик',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Физик',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Химик',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Химик',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Биолог',
                'intellect' => 'Натуралистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Биолог',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Астроном',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Астроном',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Математик',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Математик',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Геолог',
                'intellect' => 'Натуралистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Геолог',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Эколог',
                'intellect' => 'Натуралистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Эколог',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Нанотехнолог',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Нанотехнолог',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Биоинженер',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Биоинженер',
                'intellect' => 'Натуралистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Генетик',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Генетик',
                'intellect' => 'Натуралистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Специалист по квантовым технологиям',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по квантовым технологиям',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Историк',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Историк',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Лаборант в научных исследованиях',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Лаборант в научных исследованиях',
                'intellect' => 'Натуралистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Художник',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Художник',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Дизайнер',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Дизайнер',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Актёр',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Актёр',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Режиссёр',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Режиссёр',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Музыкант',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Музыкант',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Певец',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Певец',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Хореограф',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Хореограф',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Арт-директор',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Арт-директор',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Фотограф',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Фотограф',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Продюсер',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Продюсер',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Декоратор',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Декоратор',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Радиоведущий',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Радиоведущий',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Телеведущий',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Телеведущий',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Композитор',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Композитор',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Архитектор',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Архитектор',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Инженер-строитель',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Инженер-строитель',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Градостроитель',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Градостроитель',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Проектировщик конструкций',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Проектировщик конструкций',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Дизайнер интерьеров',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Дизайнер интерьеров',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Ландшафтный архитектор',
                'intellect' => 'Натуралистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Ландшафтный архитектор',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Инженер по водоснабжению и канализации',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Инженер по водоснабжению и канализации',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Инженер по отоплению и вентиляции (HVAC)',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Инженер по отоплению и вентиляции (HVAC)',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Инженер-геодезист',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Инженер-геодезист',
                'intellect' => 'Натуралистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Сметчик',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Сметчик',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Технадзор (контроль строительства)',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Технадзор (контроль строительства)',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Инженер по строительным материалам',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Инженер по строительным материалам',
                'intellect' => 'Натуралистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Прораб',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Прораб',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Инженер по противопожарным системам',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Инженер по противопожарным системам',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Маркетолог',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Маркетолог',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Бренд-менеджер',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Бренд-менеджер',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Копирайтер',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Копирайтер',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'SMM-специалист',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'SMM-специалист',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Контент-менеджер',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Контент-менеджер',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'SEO-специалист',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'SEO-специалист',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Специалист по таргетированной рекламе',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по таргетированной рекламе',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Дизайнер рекламы',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Дизайнер рекламы',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Маркетинговый аналитик',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Маркетинговый аналитик',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Специалист по PR',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по PR',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Event-менеджер (в маркетинге)',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Event-менеджер (в маркетинге)',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Исследователь рынка',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Исследователь рынка',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Менеджер по продукту',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Менеджер по продукту',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'CRM-менеджер',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'CRM-менеджер',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'PR-директор',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'PR-директор',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Тренер по спорту',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Тренер по спорту',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Спортивный врач',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Спортивный врач',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Физиотерапевт',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Физиотерапевт',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Спортсмен',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Спортсмен',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Судья спортивных соревнований',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Судья спортивных соревнований',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Менеджер спортивных мероприятий',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Менеджер спортивных мероприятий',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Инструктор по фитнесу',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Инструктор по фитнесу',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Комментатор спортивных событий',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Комментатор спортивных событий',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Инструктор по йоге',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Инструктор по йоге',
                'intellect' => 'Натуралистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Инструктор по плаванию',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Инструктор по плаванию',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Реабилитолог (в спорте)',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Реабилитолог (в спорте)',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Спортивный агент',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Спортивный агент',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Преподаватель физической культуры',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Преподаватель физической культуры',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Спортивный журналист',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Спортивный журналист',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Массажист (в спортивной сфере)',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Массажист (в спортивной сфере)',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Эксперт по спортивным тренажёрам',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Эксперт по спортивным тренажёрам',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Политолог',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Политолог',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Депутат',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Депутат',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Государственный служащий',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Государственный служащий',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Советник по государственным вопросам',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Советник по государственным вопросам',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Дипломат',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Дипломат',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Специалист по международным отношениям',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по международным отношениям',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Эксперт по правам человека',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Эксперт по правам человека',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Менеджер социальных проектов',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Менеджер социальных проектов',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Руководитель государственных программ',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Руководитель государственных программ',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Пресс-секретарь государственного органа',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Пресс-секретарь государственного органа',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Лоббист',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Лоббист',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Исследователь в области политологии',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Исследователь в области политологии',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Специалист по взаимодействию с гражданами',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по взаимодействию с гражданами',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Журналист в сфере политики',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Журналист в сфере политики',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Сотрудник посольства',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Сотрудник посольства',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Логист',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Логист',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Менеджер по транспортной логистике',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Менеджер по транспортной логистике',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Менеджер по закупкам',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Менеджер по закупкам',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Импорт-менеджер',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Импорт-менеджер',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Диспетчер транспортной компании',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Диспетчер транспортной компании',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Аналитик логистических процессов',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Аналитик логистических процессов',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Менеджер по управлению цепочками поставок',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Менеджер по управлению цепочками поставок',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Специалист по таможенному оформлению',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по таможенному оформлению',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Менеджер по управлению запасами',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Менеджер по управлению запасами',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Руководитель склада',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Руководитель склада',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Кладовщик',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Кладовщик',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Транспортный экспедитор',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Транспортный экспедитор',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Менеджер по международной логистике',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Менеджер по международной логистике',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Специалист по оптимизации логистических процессов',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по оптимизации логистических процессов',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Менеджер по организации грузоперевозок',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Менеджер по организации грузоперевозок',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Повар',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Повар',
                'intellect' => 'Натуралистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Кондитер',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Кондитер',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Швея',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Швея',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Парикмахер',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Парикмахер',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Массажист',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Массажист',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Косметолог',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Косметолог',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Авто-механик',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Авто-механик',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Автослесарь',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Автослесарь',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Мастер шиномонтажа',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Мастер шиномонтажа',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Электрик',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Электрик',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Сантехник',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Сантехник',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Водитель такси',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Водитель такси',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Специалист по ремонту бытовой техники',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по ремонту бытовой техники',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Продавец',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Продавец',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Автомеханик',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Автомеханик',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Авто-электрик',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Авто-электрик',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Инженер по двигателям',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Инженер по двигателям',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Инженер по тестированию автомобилей',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Инженер по тестированию автомобилей',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Технолог автомобильного производства',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Технолог автомобильного производства',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Мастер цеха',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Мастер цеха',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Контролер качества автомобилей',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Контролер качества автомобилей',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],
            [
                'profession' => 'Специалист по ремонту гибридных и электромобилей',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по ремонту гибридных и электромобилей',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Менеджер по продажам автомобилей',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Менеджер по продажам автомобилей',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Менеджер автопарка',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Менеджер автопарка',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Логист автотранспорта',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Логист автотранспорта',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Руководитель автосервиса',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Руководитель автосервиса',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Тюнер автомобилей',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Тюнер автомобилей',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Эксперт по автомобилям',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Эксперт по автомобилям',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Специалист по автострахованию',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по автострахованию',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Психолог-консультант',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Психолог-консультант',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Клинический психолог',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Клинический психолог',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Детский психолог',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Детский психолог',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Психотерапевт',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Психотерапевт',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Бизнес-коуч',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Бизнес-коуч',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Спортивный психолог',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Спортивный психолог',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Семейный психолог',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Семейный психолог',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Психолог-исследователь',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Психолог-исследователь',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Школьный психолог',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Школьный психолог',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Психолог-реабилитолог',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Психолог-реабилитолог',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Специалист по профориентации',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по профориентации',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Психолог для работы с подростками',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Психолог для работы с подростками',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Эксперт по психосоматике',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Эксперт по психосоматике',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Психолог-криминалист',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Психолог-криминалист',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Журналист',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Журналист',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Редактор',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Редактор',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Репортер',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Репортер',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Корреспондент',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Корреспондент',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Фото- и видеожурналист',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Фото- и видеожурналист',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Фото- и видеожурналист',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Фото- и видеожурналист',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Главный редактор',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Главный редактор',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Пресс-секретарь',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Пресс-секретарь',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Медиа-аналитик',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Медиа-аналитик',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Медиапродюсер',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Медиапродюсер',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Подкастер',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Подкастер',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Блогер',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Блогер',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Медиаконсультант',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Медиаконсультант',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Культурный обозреватель',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Культурный обозреватель',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Пожарный',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Пожарный',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Полицейский',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Полицейский',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Спасатель (МЧС)',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Спасатель (МЧС)',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Сотрудник спецслужб (КНБ, ФСБ и др.)',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Сотрудник спецслужб (КНБ, ФСБ и др.)',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Инспектор ГИБДД (ДПС)',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Инспектор ГИБДД (ДПС)',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Таможенник',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Таможенник',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Военнослужащий',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Военнослужащий',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Пограничник',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Пограничник',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Государственный инспектор',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Государственный инспектор',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Менеджер социальных проектов (в гос. секторе)',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Менеджер социальных проектов (в гос. секторе)',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Спичрайтер для госслужащих',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Спичрайтер для госслужащих',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Специалист по документообороту в госаппарате',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по документообороту в госаппарате',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Специалист по государственным закупкам',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Специалист по государственным закупкам',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Офицер по связям с общественностью',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Офицер по связям с общественностью',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Графический дизайн',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Графический дизайн',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Веб-дизайн',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Веб-дизайн',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Дизайн интерьера',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Дизайн интерьера',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Ландшафтный дизайн',
                'intellect' => 'Натуралистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Ландшафтный дизайн',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Модный дизайн',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Модный дизайн',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Промышленный дизайн',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Промышленный дизайн',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Анимационный дизайн',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Анимационный дизайн',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Дизайн игр',
                'intellect' => 'Логико-Математический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Дизайн игр',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Дизайн упаковки',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Дизайн упаковки',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Брендинг и айдентика',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Брендинг и айдентика',
                'intellect' => 'Межличностный',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Автомобильный дизайн',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Автомобильный дизайн',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Иллюстрация и концепт-арт',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Иллюстрация и концепт-арт',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Типографика и дизайн шрифтов',
                'intellect' => 'Лингвистический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Типографика и дизайн шрифтов',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Дизайн мебели',
                'intellect' => 'Телесно-Кинестетический',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Дизайн мебели',
                'intellect' => 'Логико-Математический',
                'coefficient' => 0.5,
            ],[
                'profession' => 'Арт-дирекция',
                'intellect' => 'Межличностный',
                'coefficient' => 1,
            ],
            [
                'profession' => 'Арт-дирекция',
                'intellect' => 'Лингвистический',
                'coefficient' => 0.5,
            ],
        ];

        foreach ($rawData as $item) {
            $data[] = [
                'profession_id' => Profession::query()->where('name', $item['profession'])->first()->id,
                'intellect_id' => Intellect::query()->where('name', $item['intellect'])->first()->id,
                'coefficient' => $item['coefficient'],
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        DB::transaction(function () use ($data) {
            DB::table('profession_talent')
                ->insert($data);
        });
    }
}
