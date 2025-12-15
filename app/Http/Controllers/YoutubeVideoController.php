<?php


// Ce fichier contient des fonctions qui recoivent des données par l'utilisateur (le front)
// et qui fait des choses avec ce qu'il recoit (exemple : appeler l'api youtube avec la recherche faite par l'utilisateur


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class YoutubeVideoController extends Controller
{
    // request = ce que l'utilisateur envoie (ce que nous on recoit)
    // query = ce que nous on garde de cette requete
    // response  = ce que nous on envoie en GET ou en POST

    public function search(Request $request)
    {


        $query = $request->query('q');

        if (!$query) {
            return response()->json(['error' => 'Missing query'], 400);
        }
        $response = Http::get('https://www.googleapis.com/youtube/v3/search', [
            'part' => 'snippet',
            'type' => 'video',
            'key' => env('YOUTUBE_API_KEY'),
            'q' => $query
        ]);

        if ($response->failed()) {
            return response()->json([
                'error' => 'YouTube API error',
                'status' => $response->status(),
                'body' => $response->body()
            ], 500);
        }

        return $response->json();
    }
}
