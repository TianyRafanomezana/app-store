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
        Schema::dropIfExists('habitslogs'); // <-- CORRECTION ICI
    }

    public function down(): void
    {
        Schema::create('habits_logs', function (Blueprint $table) {
            // Recrée la structure de la table ici si tu veux pouvoir rollback la suppression
        });
    }
};
