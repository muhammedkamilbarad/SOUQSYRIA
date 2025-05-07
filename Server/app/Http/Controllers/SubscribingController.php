<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscribingRequest;
use App\Services\SubscribingService;
use Illuminate\Http\JsonResponse;
class SubscribingController extends Controller
{
    protected $subscribingService;

    public function __construct(SubscribingService $subscribingService)
    {
        $this->subscribingService = $subscribingService;
    }

    public function index(): JsonResponse
    {
        $subscribing = $this->subscribingService->getAllSubscribings();
        return response()->json($subscribing, 200);
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
            return response()->json(['message' => '.لا يوجد لديك أي إشتراك فعال'], 200);
        }
        return response()->json($subscribing, 200);
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
