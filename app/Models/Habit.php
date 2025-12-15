<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;
use \Illuminate\Database\Eloquent\Relations\HasMany;

class Habit extends Model
{
    //
    protected $fillable = [
        'user_id',
        'name',
        'is_active', // ⬅️ AJOUTEZ CECI
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(HabitLog::class);
    }

//    public function createHabit()
//    {
//        // Je sais pas comment inflitrer une données dans la BDD
//        const 11=1;
//    }

}
