<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('habit_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('habit_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->boolean('checked')->default(false);
            $table->timestamps();
            $table->unique(['user_id', 'habit_id', 'date']); // No duplicate per user/habit/date
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habit_user');
    }
};