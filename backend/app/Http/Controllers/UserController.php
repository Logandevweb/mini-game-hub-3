<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Score;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        $user = $request->user();

        $bestScores = Score::select('game_id')
            ->selectRaw('MAX(score) as best_score')
            ->where('user_id', $user->id)
            ->groupBy('game_id')
            ->with('game:id,name,slug')
            ->orderByDesc('best_score')
            ->get();

        return response()->json([
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'created_at' => $user->created_at->format('d/m/Y'),
            ],
            'best_scores' => $bestScores
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'nullable|string|min:6',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        if ($request->hasFile('avatar')) {

            // Supprimer l'ancien avatar si présent
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Stocker le fichier tel quel dans storage/app/public/avatars
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return response()->json([
            'message' => 'Profil mis à jour',
            'user' => $user,
        ]);
    }
    public function index()
    {
        $users = User::select(
            'id',
            'name',
            'email',
            'role',
            'created_at'
        )->get();

        return response()->json($users);
    }
    public function destroy($id)
    {
        $user = User::find($id);

        // Vérifie si l'utilisateur existe

        if (!$user) {

            return response()->json([
                'message' => 'Utilisateur introuvable'
            ], 404);
        }

        // Empêche la suppression d'un admin

        if ($user->role === 'admin') {

            return response()->json([
                'message' => 'Impossible de supprimer un administrateur'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'message' => 'Utilisateur supprimé'
        ]);
    }
    public function destroySelf(Request $request)
    {
        $user = $request->user();

        // Supprimer l’avatar si existe
        if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
            Storage::delete('public/' . $user->avatar);
        }

        // Supprimer les scores liés
        if (method_exists($user, 'scores')) {
            $user->scores()->delete();
        }

        // Supprimer le compte
        $user->delete();

        return response()->json(['message' => 'Compte supprimé avec succès']);
    }


}
