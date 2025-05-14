<?php
namespace App\Services;

use App\Repositories\SubscriptionRequestRepository;
use App\Services\SubscribingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageUploadService;
use Illuminate\Validation\ValidationException;
use Illuminate\Pagination\LengthAwarePaginator;


class SubscriptionRequestService
{
    protected $repository;
    protected $subscribingService;
    protected $imageUploadService;

    public function __construct(
        SubscriptionRequestRepository $repository,
        SubscribingService $subscribingService,
        ImageUploadService $imageUploadService)
    {
        $this->repository = $repository;
        $this->subscribingService = $subscribingService;
        $this->imageUploadService = $imageUploadService;
    }

    public function createRequest(array $data)
    {
        // Check if user already has an active subscription
        $activeSubscription = $this->subscribingService->getCurrentActiveSubscription($data['user_id']);
        if ($activeSubscription) {
            throw ValidationException::withMessages([
                'subscription' => ['You already have an active subscription. Please wait until it expires before requesting a new one.']
            ]);
        }

        // Also check if user has a pending subscription request
        $pendingRequest = $this->repository->checkPendingByUserId($data['user_id']);
            
        if ($pendingRequest) {
            throw ValidationException::withMessages([
                'subscription' => ['You already have a pending subscription request. Please wait for it to be processed.']
            ]);
        }

        if(isset($data['receipt']))
        {
            $path = "receipts";
            $url = $this->imageUploadService->uploadImage($path, $data['receipt']);
            $data['receipt'] = $url;
        }
        return $this->repository->create($data);

        // if(isset($data['receipt']))
        // {
        //     $path = "receipts";
        //     $url = $this->imageUploadService->uploadImage($path, $data['receipt']);
        //     $data['receipt'] = $url;
        // }
        // return $this->repository->create($data);
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

    public function getAllWithRelations(array $filters=[], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getAllWithFiltersAndPagination($filters, $perPage);
    }

    public function checkPendingByUserId(int $userId)
    {
        return $this->repository->checkPendingByUserId($userId);
    }

}
