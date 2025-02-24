<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AdvertisementRequest;
use App\Services\AdvertisementService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\AdvertisementDetailResource;
use App\Http\Resources\AdvertisementCollection;
use App\Http\Requests\AdvertisementProcessRequest;

class AdvertisementController extends Controller
{
    protected $service;

    public function __construct(AdvertisementService $service)
    {
        $this->service = $service;
    }

    public function getUserAdvertisements(Request $request)
    {
        try {
            $advertisements = $this->service->getAdvertisementsByUser(request()->user());
            return response()->json([
                'success' => true,
                'data'    => new AdvertisementCollection($advertisements),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }


    public function index(Request $request)
    {
        try {
            $filters = $request->only([
                'ads_status',
                'active_status',
            ]);
            $advertisements = $this->service->getAllAdvertisements($filters);
            return response()->json([
                'success' => true,
                'data'    => new AdvertisementCollection($advertisements),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function show(int $id)
    {
        try {
            $advertisement = $this->service->getAdvertisementById($id);
            if (!$advertisement) {
                return response()->json([
                    'success' => false,
                    'message' => 'Advertisement not found'
                ], 404);
            }
            return response()->json([
                'success' => true,
                'data'    => new AdvertisementDetailResource($advertisement)
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

    public function process(AdvertisementProcessRequest $request, int $id)
    {
        try {
            $result = $this->service->processAdvertisement($id, $request->validated());
            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
