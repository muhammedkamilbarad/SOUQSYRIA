<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscribingRequest;
use App\Services\SubscribingService;
use App\Services\SubscriptionRequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\SubscriptionCollection;


class SubscribingController extends Controller
{
    protected $subscribingService;
    protected $subcriptionRequestService;

    public function __construct(SubscribingService $subscribingService, 
                                SubscriptionRequestService $subcriptionRequestService)
    {
        $this->subscribingService = $subscribingService;
        $this->subcriptionRequestService = $subcriptionRequestService;
    }

    // Get all subscriptions with pagination, filters and search
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->only([
                'package_id',
                'expiry_date_from',
                'expiry_date_to',
                'user_email',
                'is_active'
            ]);

            $perPage = $request->input('per_page', 15);
            $subscriptions = $this->subscribingService->getAllSubscribings($filters, $perPage);

            return response()->json([
                'success' => true,
                'data' => new SubscriptionCollection($subscriptions),
            ], 200);
        } catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function store(SubscribingRequest $request): JsonResponse
    {
        $subscribing = $this->subscribingService->createSubscribing($request->validated());
        return response()->json($subscribing, 201);
    }

    public function show(int $id): JsonResponse
    {
        $subscribing = $this->subscribingService->getSubscribingById($id);
        if (!$subscribing) {
            return response()->json(['message' => 'Subscribing not found'], 404);
        }
        return response()->json($subscribing, 200);
    }

    public function show_my_subscription(): JsonResponse
    {
        $userId = auth()->user()->id;
        $subscribing = $this->subscribingService->getCurrentActiveSubscription($userId);
        if (!$subscribing) {
            if ($this->subcriptionRequestService->checkPendingByUserId($userId)) {
                return response()->json([
                    'active_subscription' => false,
                    'has_pending' => true,
                    'message' => '.لديك طلب إشتراك في قائمة الإنتظار. ستتم معالجة طلبك في أسرع وقت'
                ]);
            }
            return response()->json([
                'active_subscription' => false,
                'has_pending' => false,
                'message' => '.لا يوجد لديك أي إشتراك فعال'
            ], 200);
        }
        return response()->json([
            $subscribing,
        ], 200);
    }


    public function destroy(int $id): JsonResponse
    {
        $subscribing = $this->subscribingService->getSubscribingById($id);
        if (!$subscribing) {
            return response()->json(['message' => 'Subscribing not found'], 404);
        }

        $this->subscribingService->deleteSubscribing($subscribing);
        return response()->json(['message' => 'Subscribing deleted successfully'], 200);
    }
}
