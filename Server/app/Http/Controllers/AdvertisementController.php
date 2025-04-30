<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AdvertisementRequest;
use App\Services\AdvertisementService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\AdvertisementDetailResource;
use App\Http\Resources\AdvertisementCollection;
use App\Http\Requests\AdvertisementProcessRequest;
use App\Http\Requests\AdvertisementUpdateRequest;
use App\Http\Resources\AdvertisementResource;
use App\Http\Resources\SimilarAdvertisementCollection;




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
            $filters = $request->only([
                'ads_status',
                'active_status',
                'category_id',
                'type',
            ]);
            $perPage = $request->get('per_page', 5);
            $result = $this->service->getAdvertisementsByUser(request()->user(), $filters, $perPage);
            return response()->json([
                'success' => true,
                'stats'   => $result['stats'],
                'data'    => new AdvertisementCollection($result['advertisements']),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function getUserAdvertisementByIdAndSlug(int $id, string $slug)
    {
        try {
            $advertisement = $this->service->getAdvertisementForOwnerUser(request()->user()->id, $id, $slug);
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


    public function index(Request $request)
    {
        try {
            $filters = $request->only([
                'ads_status',
                'active_status',
                'category_id',
                'user_query',
                'city',
            ]);
            $perPage = $request->get('per_page', 5);
            $advertisements = $this->service->getAllAdvertisements($filters, $perPage);
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

    public function showByIdAndSlug(int $id, string $slug)
    {
        try {
            $advertisement = $this->service->getAdvertisementByIdAndSlug($id, $slug);
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

    public function destroy(int $advId)
    {
        try {
            $result = $this->service->deleteAdvertisement($advId);
            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function update(AdvertisementUpdateRequest $request, int $id)
    {
        try {
            $result = $this->service->updateAdvertisement($id, $request->validated(), $request->user());
            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function getAdvertisementsForHomePage(Request $request)
    {
        try {
            $filters = $request->only([
                'category',
                'type',
                'city',
                'minPrice',
                'maxPrice',
                'search',
                'sort_by',
                'sort_direction',
                'brand',
                'model',
                'color',
                'fuel_type',
                'transmission_type',
                'number_of_rooms',
                'house_type',
                'motorcycle_type',
                'cooling_type',
                'marine_type',
                'car_type',
                'per_page',
                'condition',
                'year',
                'min_square_meters',
                'max_square_meters',
            ]);
            $perPage = $request->get('per_page', 30);
            $advertisements = $this->service->getAllAdvertisementsForHomePage($filters, $perPage);
            //\Log::info($advertisements->toSql());
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


    public function deactivateAdvertisement(int $advId)
    {
        try {
            $result = $this->service->deactivateAdvertisementByUser($advId, request()->user());
            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function activateAdvertisement(int $advId)
    {
        try {
            $result = $this->service->activateAdvertisementByUser($advId, request()->user());
            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function advertisementDetails(Request $request, int $id)
    {
        try {
            $limit = $request->get('limit', 5);

            // Validate limit
            if (!is_numeric($limit) || $limit < 1 || $limit > 50) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid limit parameter. Must be between 1 and 50.'
                ], 400);
            }

            // Fetch main advertisement
            $advertisement = $this->service->getAdvertisementById($id);
            if (!$advertisement) {
                return response()->json([
                    'success' => false,
                    'message' => 'Advertisement not found'
                ], 404);
            }

            // Fetch similar advertisements
            $similarAds = $this->service->getSimilarAdvertisements($id, (int)$limit);

            return response()->json([
                'success' => true,
                'data' => [
                    'advertisement' => new AdvertisementDetailResource($advertisement),
                    'similar_advertisements' => new SimilarAdvertisementCollection($similarAds),
                ]
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error showing advertisement details: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the advertisement'
            ], 500);
        }
    }
}
