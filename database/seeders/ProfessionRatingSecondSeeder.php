<?php

namespace Database\Seeders;

use App\Models\Profession;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfessionRatingSecondSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rawData = [
            [
                'name' => 'Продавец-консультант в люксовых магазинах',
                'rating' => 87,
                'man' => 85,
                'woman' => 93,
            ],
            [
                'name' => 'Специалист по разработке учебных материалов',
                'rating' => 98,
                'man' => 86,
                'woman' => 93,
            ],[
                'name' => 'Профессор или преподаватель социальных наук',
                'rating' => 90,
                'man' => 87,
                'woman' => 93,
            ],[
                'name' => 'UX/UI дизайн',
                'rating' => 100,
                'man' => 91,
                'woman' => 93,
            ],[
                'name' => 'Инженер-строитель (архитектура)',
                'rating' => 92,
                'man' => 98,
                'woman' => 85,
            ],[
                'name' => 'Специалист по маркетплейсам',
                'rating' => 95,
                'man' => 91,
                'woman' => 93,
            ],[
                'name' => 'Проектировщик мостов и туннелей',
                'rating' => 91,
                'man' => 98,
                'woman' => 83,
            ]
        ];

        DB::transaction(function () use ($rawData) {
            foreach ($rawData as $item) {
                DB::table('professions')
                    ->where('name', $item['name'])
                    ->update([
                        'rating' => $item['rating'],
                        'man' => $item['man'],
                        'woman' => $item['woman'],
                    ]);
            }
        });
    }
}
