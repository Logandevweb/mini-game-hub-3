<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Score;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'game' => 'required|string',
            'score' => 'required|integer',
        ]);

        $game = Game::where('slug', $request->game)->firstOrFail();
        
        if ($request->score < 0 || $request->score > 1000000) {
            return response()->json(['error' => 'Score invalide ou suspect'], 422);
        }

        $score = Score::create([
            'user_id' => $request->user()->id,
            'game_id' => $game->id,
            'score' => $request->score,
        ]);

        return response()->json([
            'message' => 'Score enregistré avec succès',
            'score' => $score
        ], 201);
    }
    public function bestScores($gameSlug)
    {
        $game = Game::where('slug', $gameSlug)->firstOrFail();

        $bestScores = Score::where('game_id', $game->id)
            ->orderByDesc('score')
            ->limit(10)
            ->with('user:id,name,avatar')
            ->get();

        return response()->json([
            'game' => $game->name,
            'best_scores' => $bestScores
        ]);
    }
    public function allScores()
    {
        $games = Game::with(['scores' => function ($query) {
            $query->with('user:id,name,avatar')
                ->orderByDesc('score');
        }])->get();

        $result = [];

        foreach ($games as $game) {
            $result[] = [
                'slug' => $game->slug,
                'name' => $game->name,
                'scores' => $game->scores
            ];
        }

        return response()->json($result);
    }
    public function userScores(Request $request)
    {
        $user = $request->user();

        // Récupérer tous les jeux
        $games = Game::all();

        $result = [];

        foreach ($games as $game) {
            $scores = Score::where('user_id', $user->id)
                ->where('game_id', $game->id)
                ->orderByDesc('score')
                ->get(['id', 'score']);

            $result[] = [
                'slug' => $game->slug,
                'name' => $game->name,
                'scores' => $scores,
            ];
        }

        return response()->json($result);
    }


}
