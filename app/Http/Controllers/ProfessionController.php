<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profession;
use Illuminate\Support\Facades\Auth;

class ProfessionController extends Controller
{
    public function addToFavorites(Request $request)
    {
        $request->validate([
            'profession_id' => 'required|integer|exists:professions,id'
        ]);

        $user = Auth::user();
        $professionId = $request->profession_id;
        
        $favoriteProfessions = $user->favorite_professions ?? [];
        
        if (!in_array($professionId, $favoriteProfessions)) {
            $favoriteProfessions[] = $professionId;
            $user->update(['favorite_professions' => $favoriteProfessions]);
            
            return response()->json([
                'success' => true,
                'message' => 'Профессия добавлена в избранное!'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Профессия уже в избранном'
        ]);
    }

    public function removeFromFavorites(Request $request)
    {
        $request->validate([
            'profession_id' => 'required|integer|exists:professions,id'
        ]);

        $user = Auth::user();
        $professionId = $request->profession_id;
        
        $favoriteProfessions = $user->favorite_professions ?? [];
        
        $favoriteProfessions = array_filter($favoriteProfessions, function($id) use ($professionId) {
            return $id != $professionId;
        });
        
        $user->update(['favorite_professions' => array_values($favoriteProfessions)]);
        
        return response()->json([
            'success' => true,
            'message' => 'Профессия удалена из избранного!'
        ]);
    }
}
