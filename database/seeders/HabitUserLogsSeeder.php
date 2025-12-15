<?php

// database/seeders/HabitUserLogsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class HabitUserLogsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = 2; // L'utilisateur cible (toto)

        // Liste des IDs d'habitudes actives pour l'utilisateur 2
        // IDs : 1 (Manger bien), 3 (Kiker), 4 (Cool)
        // NOTE: J'exclus l'habitude ID 2 (5L d'eau) car son is_active est à 0 dans votre exemple.
        $activeHabitIds = [1, 3, 4];

        $logsToInsert = [];
        $startDate = Carbon::now()->subDays(30); // Commence il y a 30 jours
        $endDate = Carbon::now()->subDays(); // Jusqu'à aujourd'hui

        // 1. Boucle sur les 30 derniers jours
        while ($startDate->lessThanOrEqualTo($endDate)) {
            $currentDate = $startDate->toDateString();

            // 2. Boucle sur chaque habitude active
            foreach ($activeHabitIds as $habitId) {

                // Définir un état aléatoire (environ 70% de chance d'être coché)
                $checked = (bool) (rand(1, 100) <= 70);

                // Si c'est aujourd'hui, assurez-vous que les logs correspondent aux données actuelles
                // (Optionnel : pour éviter d'écraser un état réel, mais pour un seeder, l'aléatoire est ok)

                $logsToInsert[] = [
                    'user_id' => $userId,
                    'habit_id' => $habitId,
                    'date' => $currentDate,
                    'checked' => $checked,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $startDate->addDay();
        }

        // 3. Insertion en une seule fois (Bulk Insert) pour la performance
        DB::table('habit_user')->insert($logsToInsert);

        $this->command->info('Logs d\'habitudes générés pour 30 jours pour l\'utilisateur ID 2.');
    }
}
