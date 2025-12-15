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
        Schema::create('habitslogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('habit_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->boolean('checked')->default(false);
            $table->unique(['habit_id', 'date']); // évite les doublons
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habitslogs');
    }
};
