<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\SubscribingService;
use App\Repositories\SubscriptionRequestRepository;

class SubscribingRequest extends FormRequest
{
    protected $subscribingService;
    protected $subscriptionRequestRepository;

    // Create a new request instance.
    public function __construct(
        SubscribingService $subscribingService = null,
        SubscriptionRequestRepository $subscriptionRequestRepository = null
    ) {
        $this->subscribingService = $subscribingService ?? app(SubscribingService::class);
        $this->subscriptionRequestRepository = $subscriptionRequestRepository ?? app(SubscriptionRequestRepository::class);
    }


    public function rules(): array
    {
        return $this->storeRules();
    }

    public function storeRules(): array
    {
        return [
            'user_id'    => 'required|exists:users,id',
            'package_id' => 'required|exists:packages,id',
            // we are going to set purchase_date, expiry_date, remaining_ads internally
            // so we don't need them from request if your logic doesn't want them from user
        ];
    }
    public function messages()
    {
        return [
            'user_id.required' => '.معرف المستخدم مطلوب',
            'user_id.exists' => '.معرف المستخدم المحدد غير صالح',
            
            'package_id.required' => '.معرف الحزمة مطلوب',
            'package_id.exists' => '.معرف هذه الباقة غير صالح',
        ];
    }

    // Configure the validator instance.
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $userId = $this->input('user_id');
            
            // Check if user already has an active subscription
            $activeSubscription = $this->subscribingService->getCurrentActiveSubscription($userId);
            
            if ($activeSubscription) {
                $validator->errors()->add(
                    'subscription', '.يوجد بالفعل اشتراك نشط لهذا المستخدم. يرجى الانتظار حتى ينتهي قبل إنشاء اشتراك جديد'
                );
            }
            
            // Also check if user already has a pending request
            $pendingRequest = $this->subscriptionRequestRepository->checkPendingByUserId($userId);
            
            if ($pendingRequest) {
                $validator->errors()->add(
                    'subscription', '.يوجد بالفعل طلب اشتراك قيد المعالجة لهذا المستخدم. يرجى الانتظار حتى تتم معالجته'
                );
            }
        });
    }

}