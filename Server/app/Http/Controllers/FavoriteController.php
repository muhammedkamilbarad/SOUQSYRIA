<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\FavoriteRequest;
use App\Services\FavoriteService;
use App\Http\Resources\FavoriteCollection;

class FavoriteController extends Controller
{
    protected $favoriteService;

    public function __construct(FavoriteService $favoriteService)
    {
        $this->favoriteService = $favoriteService;
        $this->middleware('auth'); // This is for ensuring user is authenticated
    }

    public function addToFavorites(FavoriteRequest $request)
    {
        $userId = auth()->id(); // getting the user who is authenticated
        $advertisementId = $request->advs_id;

        $favorite = $this->favoriteService->addFavorite($userId, $advertisementId);

        return response()->json([
            'success' => true, 
            'message' => 'Advertisement added to favorites',
            'data' => $favorite
        ], 201);
    }

    public function removeFromFavorites(int $advertisementId)
    {
        $userId = auth()->id(); // getting the user who is authenticated

        $result = $this->favoriteService->removeFavorite($userId, $advertisementId);

        if ($result)
        {
            return response()->json([
                'success' => true,
                'message' => 'Advertisement removed from favorites'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Favorite not found'
        ], 404);
    }

    public function getUserFavorites()
    {
        $userId = auth()->id(); // getting the user who is authenticated

        $favorites = $this->favoriteService->getUserFavorites($userId);

        

        return response()->json([
            'success' => true,
            'data' => new FavoriteCollection($favorites),
        ], 200);
    }


}
