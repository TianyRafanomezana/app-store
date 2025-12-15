<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHabitRequest;
use App\Models\Habit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

//D'abord je regarde la table habit puis après je retourne l'état. 
// Le index revoie les habits-user de aujourd'hui -> si il n'a rien coché il renvoie j

class HabitController extends Controller
{

    
    // app/Http/Controllers/HabitController.php

public function index()
{
    $user = auth()->user(); 
    $today = now()->toDateString();

    $habits = $user->habits() 
        // 💡 Changement : On filtre pour ne prendre que les habitudes actives
        ->where('is_active', true) 
        ->get()
        ->map(function ($habit) use ($today) {
            // ... (Logique de log inchangée) ...
            $habitUser = DB::table('habit_user') 
                ->where('user_id', Auth::id())
                ->where('habit_id', $habit->id)
                ->where('date', $today)
                ->first(); 

            return [
                'id' => $habit->id,
                'name' => $habit->name,
                'checked' => $habitUser ? (bool) $habitUser->checked : false,
            ];
        });

    return Inertia::render('dashboard', [
        'habits' => $habits,
    ]);
}

    // Cette fonction nous permet de rajouter de nouvelles habitudes
    // On veut recevoir un nom de tache puis l'insérer dans notre table Habits

//    public function store(StoreHabitRequest $request, HabitService $service)  // request c'est l'utilisateur
//    {
//        // Je veux qu'il recoive mon string
//        // Ensuite qu'il créé une entrée dans la colone Habit lié à l'id de l'utilisateur, puis qu'il créé un HabitLog associé
//
//        return $service->createHabit($request);//fonction qui créé une entrée dans 'Habit'
//    }

    public function store(StoreHabitRequest $request)
    {
        $habit = auth()->user()->habits()->create([
            'name' => $request->name,
        ]);

        return redirect()->back();
    }

    public function destroy(Request $request, Habit $habit) 
{
    // 1. VÉRIFICATION D'APPARTENANCE (Inchangée, la sécurité est la même)
    if ($habit->user_id !== Auth::id()) {
        abort(403, 'Action non autorisée. Cette habitude ne vous appartient pas.');
    }

    // 2. DÉSACTIVATION (Au lieu de la suppression)
    // On met le drapeau à false. L'habitude et tous ses logs restent en BDD.
    $habit->update(['is_active' => false]);

    // 3. REDIRECTION
    return redirect()->back()->with('success', 'Habitude archivée avec succès. Les données de suivi ont été conservées.');
}


}
