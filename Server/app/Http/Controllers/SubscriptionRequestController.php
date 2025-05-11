<?php
namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionRequestStoreRequest;
use App\Http\Requests\SubscriptionRequestProcessRequest;
use App\Services\SubscriptionRequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\SubscriptionRequestCollection;

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

    public function index(Request $request)
    {
        try {
            $filters = $request->only([
                'status',
                'created_at_from',
                'created_at_to',
                'processed_at_from',
                'processed_at_to',
                'package_id',
                'user_email'
            ]);

            $perPage = $request->input('per_page', 15);

            $subscriptionRequests = $this->service->getAllWithRelations($filters, $perPage);

            return response()->json([
                'success' => true,
                'data' => new SubscriptionRequestCollection($subscriptionRequests),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'=> $e->getMessage(),
            ], 400);
        }
    }
}
