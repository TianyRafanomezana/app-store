<?php
// database/migrations/YYYY_MM_DD_add_is_active_to_habits_table.php

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
        // On modifie la table 'habits'
        Schema::table('habits', function (Blueprint $table) {
            
            // Ajout de la colonne 'is_active' après 'name'.
            // Valeur par défaut à 'true' pour que toutes les habitudes existantes soient actives.
            $table->boolean('is_active')
                  ->default(true)
                  ->after('name')
                  ->comment('Indique si l\'habitude est active (true) ou archivée (false).');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // On s'assure que si la migration est annulée, la colonne est supprimée.
        Schema::table('habits', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};