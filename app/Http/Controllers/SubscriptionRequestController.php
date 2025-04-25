<?php
namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionRequestStoreRequest;
use App\Http\Requests\SubscriptionRequestProcessRequest;
use App\Services\SubscriptionRequestService;
use Illuminate\Http\JsonResponse;

class SubscriptionRequestController extends Controller
{
    protected $service;
    public function __construct(SubscriptionRequestService $subscriptionRequestService)
    {
        $this->service = $subscriptionRequestService;
    }

    public function store(SubscriptionRequestStoreRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $subscriptionRequest = $this->service->createRequest($data);
        return response()->json($subscriptionRequest, 201);
    }

    public function process(SubscriptionRequestProcessRequest $request, int $id)
    {
        $data = $request->validated();
        $subscriptionRequest = $this->service->processRequest($id, $data);
        return response()->json($subscriptionRequest, 200);
    }

    public function index()
    {
        $subscriptionRequests = $this->service->getAllWithRelations();
        return response()->json($subscriptionRequests, 200);
    }
}
