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
            return response()->json(['message' => 'Subscribing Not Found'], 404);
        }
        return response()->json($subscribing, 200);
    }

    public function update(SubscribingRequest $request, int $id): JsonResponse
    {
        $subscribing = $this->subscribingService->getSubscribingById($id);
        if (!$subscribing) {
            return response()->json(['message' => 'Subscribing Not Found'], 404);
        }
        
        // If we want to do the "promote" logic (expiry_date, remaining_ads) 
        // we rely on the updateSubscribing method from service
        $updatedSubscribing = $this->subscribingService->updateSubscribing($subscribing, $request->validated());
        return response()->json($updatedSubscribing, 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $subscribing = $this->subscribingService->getSubscribingById($id);
        if (!$subscribing) {
            return response()->json(['message' => 'Subscribing Not Found'], 404);
        }

        $this->subscribingService->deleteSubscribing($subscribing);
        return response()->json(['message' => 'Subscribing Deleted'], 200);
    }
}
