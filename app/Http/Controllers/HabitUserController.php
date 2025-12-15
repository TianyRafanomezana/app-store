<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Habit; // Assurez-vous d'importer le modèle Habit si vous l'utilisez

class HabitUserController extends Controller
{
    /**
     * Enregistrer (ou mettre à jour) l'état d'une habitude pour un utilisateur à une date précise.
     *
     * EXPLICATION DE updateOrInsert :
     * --------------------------------
     * updateOrInsert() est une méthode Laravel qui combine INSERT et UPDATE en une seule opération.
     * Elle prend DEUX tableaux en paramètres :
     *
     * 1. PREMIER TABLEAU (conditions de recherche) :
     *    - Laravel cherche une ligne dans la table qui correspond EXACTEMENT à ces valeurs
     *    - Si une ligne existe avec ces valeurs (user_id, habit_id, date), elle sera MISE À JOUR
     *    - Si aucune ligne n'existe, une NOUVELLE ligne sera CRÉÉE
     *
     * 2. DEUXIÈME TABLEAU (valeurs à insérer/mettre à jour) :
     *    - Si la ligne existe : ces valeurs remplacent les anciennes valeurs
     *    - Si la ligne n'existe pas : ces valeurs sont utilisées pour créer la nouvelle ligne
     *
     * EXEMPLE CONCRET :
     * -----------------
     * Supposons que l'utilisateur 1 coche l'habitude 5 pour le 2024-01-15 :
     *
     * Premier appel (ligne n'existe pas) :
     *   - Laravel cherche : user_id=1, habit_id=5, date='2024-01-15' → PAS TROUVÉ
     *   - Action : INSERT avec checked=true, created_at=now(), updated_at=now()
     *
     * Deuxième appel (même utilisateur, même habitude, même date) :
     *   - Laravel cherche : user_id=1, habit_id=5, date='2024-01-15' → TROUVÉ !
     *   - Action : UPDATE checked=false, updated_at=now() (created_at reste inchangé)
     *
     * NOTE IMPORTANTE :
     * -----------------
     * La contrainte UNIQUE sur (user_id, habit_id, date) dans la migration garantit
     * qu'il ne peut y avoir qu'UNE SEULE entrée par combinaison user/habit/date.
     */
    public function storeOrUpdate(Request $request)
    {
        // Validation des données entrantes
        $request->validate([
            'habit_id' => 'required|exists:habits,id', // L'habitude doit exister
            'date' => 'required|date', // La date doit être valide
            'checked' => 'required|boolean', // checked doit être true ou false
        ]);

        // Récupération de l'ID de l'utilisateur actuellement authentifié
        $userId = Auth::id();

        // updateOrInsert : recherche ou crée une entrée dans la table habit_user
        // Premier tableau : conditions de recherche (clés composées)
        //   - Si une ligne avec ces 3 valeurs existe → UPDATE
        //   - Si aucune ligne n'existe → INSERT
        DB::table('habit_user')->updateOrInsert(
            // PREMIER TABLEAU : Conditions de recherche (identifiant unique)
            // Laravel cherche une ligne avec ces valeurs exactes
            [
                'user_id' => $userId, // L'utilisateur connecté
                'habit_id' => $request->habit_id, // L'habitude concernée
                'date' => $request->date, // La date concernée
            ],
            // DEUXIÈME TABLEAU : Valeurs à insérer/mettre à jour
            // Ces valeurs seront utilisées pour UPDATE (si ligne existe) ou INSERT (si ligne n'existe pas)
            [
                'checked' => $request->checked, // L'état de l'habitude (cochée ou non)
                'updated_at' => now(), // Toujours mettre à jour le timestamp
                'created_at' => now(), // Si INSERT : définit created_at, si UPDATE : Laravel l'ignore
            ]
        );

        return redirect()->route('dashboard');
    }

    /**
     * Récupérer l'état d'une habitude pour une date précise (par utilisateur connecté).
     *
     * Cette méthode permet de vérifier si un utilisateur a coché une habitude
     * pour une date spécifique.
     */
    public function show(Request $request)
    {
        // Validation des paramètres de requête
        $request->validate([
            'habit_id' => 'required|exists:habits,id', // L'habitude doit exister
            'date' => 'required|date', // La date doit être valide
        ]);

        // Recherche de l'entrée dans la table habit_user
        // On cherche une ligne correspondant à l'utilisateur connecté, l'habitude et la date demandés
        $log = DB::table('habit_user')
            ->where('user_id', Auth::id()) // Filtre par utilisateur connecté
            ->where('habit_id', $request->habit_id) // Filtre par habitude
            ->where('date', $request->date) // Filtre par date
            ->first(); // Récupère la première ligne trouvée (ou null si aucune)

        // Retourne l'état checked (true/false) ou false par défaut si aucune entrée n'existe
        return response()->json(['checked' => $log->checked ?? false]);
    }

    // app/Http/Controllers/HabitUserController.php (Méthode history)
/**
 * Récupérer l'historique d'une ou plusieurs habitudes pour le calcul de statistiques.
 * Cette méthode ne retourne pas de vue, mais des données JSON.
 * * Route : GET /habit-user/history
 * Attend : habit_id (optionnel, pour cibler une seule habitude)
 * Retourne : Tableau des logs triés par date.
 */
// app/Http/Controllers/HabitUserController.php

public function history(Request $request)
{
    // ... (Validation inchangée) ...
    $request->validate([
        'habit_id' => 'sometimes|exists:habits,id',
        'days' => 'sometimes|integer|min:7|max:365', // Validation que 'days' est un nombre
    ]);

    $userId = Auth::id();
    // 💡 1. Récupération du paramètre 'days' avec une valeur par défaut de 30
    $days = $request->input('days', 30); 

    // 💡 2. Calcul de la date de début
    $startDate = now()->subDays($days)->toDateString();
    
    // 3. Requête de base
    $query = DB::table('habit_user')
        ->where('user_id', $userId)
        // 💡 3. Appliquer le filtre de date
        ->where('date', '>=', $startDate) 
        ->orderBy('date', 'asc');

    // ... (Application du filtre d'habitude si demandé - inchangé) ...

    if ($request->has('habit_id')) {
        $habitId = $request->input('habit_id');

        // Sécurité: (Vérification de propriété)
        if (!Habit::where('id', $habitId)->where('user_id', $userId)->exists()) {
             return response()->json(['error' => 'Habitude non trouvée ou non autorisée.'], 404);
        }
        
        $query->where('habit_id', $habitId);
    }

    // 4. Exécution et retour
    $logs = $query->get();
    return response()->json($logs);
}
}
