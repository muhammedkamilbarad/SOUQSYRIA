<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AdvertisementRequest;
use App\Services\AdvertisementService;
use Illuminate\Http\JsonResponse;


class AdvertisementController extends Controller
{
    protected $service;

    public function __construct(AdvertisementService $service)
    {
        $this->service = $service;
    }

    /*
     * Get all advertisements with related details.
     */
    public function index()
    {
        try {
            // Fetch data with all needed eager loads
            $advertisements = $this->service->getAllAdvertisements();

            // Transform each advertisement to remove null relationships
            $transformed = $advertisements->map(function ($ad) {
                $array = $ad->toArray();

                // Remove all keys where the value is null
                return collect($array)->reject(fn($value) => is_null($value))->toArray();
            });

            // Return JSON response
            return response()->json([
                'success' => true,
                'data'    => $transformed
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function store(AdvertisementRequest $request)
    {
        try {
            $result = $this->service->create($request->validated(), $request->user());
            return response()->json($result, 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
