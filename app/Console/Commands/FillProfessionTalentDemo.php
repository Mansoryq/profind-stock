<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Profession;
use App\Models\Talent;
use Illuminate\Support\Facades\DB;

class FillProfessionTalentDemo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:fill-profession-talent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Заполнить таблицу profession_talent демо-данными: каждой профессии 8 талантов с коэффициентами от 0 до 1';

    public function handle()
    {
        $this->info('Очистка таблицы profession_talent...');
        DB::table('profession_talent')->truncate();

        $professions = Profession::orderBy('id')->get();
        $talents = Talent::orderBy('id')->get();

        if ($talents->count() < 8) {
            $this->error('В базе должно быть минимум 8 талантов!');
            return 1;
        }

        foreach ($professions as $i => $profession) {
            $talentIds = $talents->pluck('id')->toArray();
            $offset = ($i * 2) % ($talents->count() - 7); // другой сдвиг для разнообразия
            $selected = array_slice($talentIds, $offset, 8);

            $coefficients = [0.91, 0.83, 0.75, 0.68, 0.57, 0.39, 0.21, 0.09]; // другие значения

            foreach ($selected as $j => $talentId) {
                $coefficient = $coefficients[$j];
                $profession->talents()->attach($talentId, ['coefficient' => $coefficient]);
            }
            $this->info("Профессия {$profession->name}: таланты " . implode(',', $selected));
        }

        $this->info('Демо-наполнение завершено!');
        return 0;
    }
}
