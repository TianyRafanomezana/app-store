<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HabitLogController extends Controller
{
    // Dans cette fonction on veut enregistrer l'état d'une habitude dans notre BDD
    // On

    public function toggle(Habit $habit)
    {
        $today = now()->toDateString();

        $log = $habit->logs()->firstOrCreate([
            'date' => $today,
        ]);

        $log->checked = ! $log->checked;
        $log->save();

        return $log;
    }
}
