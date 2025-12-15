<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Habit;
use App\Models\HabitLog;
use Illuminate\Support\Facades\Hash;

class HabitsSeeder extends Seeder
{
    public function run(): void
    {
        // Crée un utilisateur de test
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);
        // Crée 3 habitudes pour ce user
        $habits = [
            'Boire de l’eau',
            'Faire du sport',
            'Lire un livre',
        ];
        foreach ($habits as $habitName) {
            $habit = $user->habits()->create([
                'name' => $habitName,
            ]);
            // Ajoute des logs sur plusieurs jours
            foreach ([now()->subDays(1), now()] as $day) {
                $habit->logs()->create([
                    'date' => $day->toDateString(),
                    'checked' => rand(0, 1),
                ]);
            }
        }
    }
}
