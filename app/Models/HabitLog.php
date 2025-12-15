<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;

class HabitLog extends Model
{
    protected $fillable = ['habit_id', 'date', 'is_done']; // Constructeur ???
    protected $table = 'habits_logs';   // si c'est ton vrai nom !

    function habit(): BelongsTo
    {
        return $this->belongsTo(Habit::class); // Renvoie l'habitude lié

    }
}
