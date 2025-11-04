<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Fav;
use App\Models\Product;
use App\Services\FakeStoreService;
use Illuminate\Support\Facades\Auth;

class FavController extends Controller
{
    protected $fakeStoreService;

    public function __construct(FakeStoreService $fakeStoreService)
    {
        $this->fakeStoreService = $fakeStoreService;
    }

    /**
     * GET /api/clients/{client}/favs
     */
    public function index(Client $client)
    {
        if (Auth::id() !== $client->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $favorites = $client->favorites()->get();

        $formattedFavorites = $favorites->map(function ($fav) {
            return $this->formatFavResponse($fav);
        });

        return response()->json($formattedFavorites);
    }

    /**
     * POST /api/clients/{client}/favs
     */
    public function store(Request $request, Client $client)
    {
        if (Auth::id() !== $client->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }
        $request->validate([
            'external_product_id' => 'required|integer',
            'review' => 'nullable|string|max:500',
        ]);
        $externalId = $request->input('external_product_id');
        $product = $this->fakeStoreService->fetchAndCacheProduct($externalId);

        if (is_null($product)) {
            return response()->json(['message' => 'Product not found on API.'], 404);
        }

        if ($client->favorites()->where('product_id', $product->id)->exists()) {
            return response()->json(['message' => 'This product is already a favorite.'], 409);
        }

        $client->favorites()->attach($product->id, [
            'review' => $request->review,
        ]);

        $newFav = $client->favorites()->where('product_id', $product->id)->first();

        return response()->json([
            'message' => 'Product added to favorites.',
            'favorite' => $this->formatFavResponse($newFav)
        ], 201);
    }

    /**
     * DELETE /api/clients/{client}/favs/{product_id_externo}
     */
    public function destroy(Client $client, int $external_product_id)
    {
        if (Auth::id() !== $client->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $product = Product::where('external_id', $external_product_id)->first();
        if (!$product) {
            return response()->json(['message' => 'Product not found on DB.'], 404);
        }

        $deletedCount = $client->favorites()->detach($product->id);
        if ($deletedCount === 0) {
            return response()->json(['message' => 'Favorite not found.'], 404);
        }

        return response()->json(null, 204);
    }

    protected function formatFavResponse($fav)
    {
        return [
            'id' => $fav->product_id,
            'title' => $fav->title,
            'image' => $fav->image,
            'price' => $fav->price,
            'review' => $fav->review,
            'favorite_id_local' => $fav->id
        ];
    }
}
