<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void // Fonction qui créé ma table
    {
        Schema::create('habits', function (Blueprint $table) { // Langage qui créé ma table, Blue print je sais pas ce que ca, "imprimer je suppose"
            $table->id(); // Sur l'object table que je recoit j'applique la méthode id => ca me créé un id ? Oui, i guess
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // Il applique une méthode string() qui créé un string (colone)
            $table->timestamps(); // C'est pour faire une copie enregistrée de mon habitude (pour les 1000 habitudes créées chaque années
        });
        //Ma fonction recoit un objet "blueprint" qui est une table SQL je pense

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habits');
    }
};


// Une fonction qui recoit un object Blueprint $table qui est une table SQL vide
