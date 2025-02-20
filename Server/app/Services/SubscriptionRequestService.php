<?php
namespace App\Services;

use App\Repositories\SubscriptionRequestRepository;
use App\Services\SubscribingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class SubscriptionRequestService
{
    protected $repository;
    protected $subscribingService;

    public function __construct(
        SubscriptionRequestRepository $repository,
        SubscribingService $subscribingService)
    {
        $this->repository = $repository;
        $this->subscribingService = $subscribingService;
    }

    public function createRequest(array $data)
    {
        if(isset($data['receipt']))
        {
            $url = $data['receipt']->store('receipts','public');
            $data['receipt'] = $url;
        }
        return $this->repository->create($data);
    }

    public function processRequest(int $requestId, array $data)
    {
        $request = $this->repository->getById($requestId);
        $request->status= $data['status'];
        $request->message = $data['message'] ?? null;
        $request->processed_at = Carbon::now();
        if($data['status'] === 'approved')
        {
            $activeSubscription = $this->subscribingService->getCurrentActiveSubscription($request->user_id);
            if($activeSubscription)
            {
                $this->subscribingService->updateSubscribing($activeSubscription, ['package_id'=>$request->package_id]);
            }
            else
            {
                $this->subscribingService->createSubscribing([
                    'user_id' => $request->user_id,
                    'package_id' => $request->package_id,
                ]);
            }
        }
        return $this->repository->update($request, $request->toArray());
    }

    public function getAllWithRelations()
    {
        return $this->repository->getAllWithRelations();
    }

}
