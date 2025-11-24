<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sphere;
use App\Models\Talent;
use Illuminate\Support\Facades\DB;

class FillSphereTalentDemo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:fill-sphere-talent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Заполнить таблицу sphere_talent демо-данными: каждой сфере 8 талантов с коэффициентами от 0 до 1';

    public function handle()
    {
        $this->info('Очистка таблицы sphere_talent...');
        DB::table('sphere_talent')->truncate();

        $spheres = Sphere::orderBy('id')->get();
        $talents = Talent::orderBy('id')->get();

        if ($talents->count() < 8) {
            $this->error('В базе должно быть минимум 8 талантов!');
            return 1;
        }

        // Пример соответствия: для каждой сферы берем 8 следующих талантов по порядку, сдвигая старт
        foreach ($spheres as $i => $sphere) {
            $talentIds = $talents->pluck('id')->toArray();
            // Сдвиг для разнообразия
            $offset = ($i * 3) % ($talents->count() - 7);
            $selected = array_slice($talentIds, $offset, 8);

            $coefficients = [0.95, 0.82, 0.77, 0.66, 0.54, 0.41, 0.23, 0.11]; // Пример: убывающие значения

            foreach ($selected as $j => $talentId) {
                $coefficient = $coefficients[$j];
                $sphere->talents()->attach($talentId, ['coefficient' => $coefficient]);
            }
            $this->info("Сфера {$sphere->name}: таланты " . implode(',', $selected));
        }

        $this->info('Демо-наполнение завершено!');
        return 0;
    }
}
