<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\SubscribingService;
use App\Repositories\SubscriptionRequestRepository;

class SubscriptionRequestStoreRequest extends FormRequest
{
    protected $subscribingService;
    protected $subscriptionRequestRepository;

    // Create a new request instance.
    public function __construct(
        SubscribingService $subscribingService,
        SubscriptionRequestRepository $subscriptionRequestRepository
    ) {
        $this->subscribingService = $subscribingService;
        $this->subscriptionRequestRepository = $subscriptionRequestRepository;
    }

    public function rules(): array
    {
        return [
            'package_id' => 'required|exists:packages,id',
            'receipt' => 'required|file|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'package_id.required' => '.معرف الباقة مطلوب',
            'package_id.exists' => '.معرف الباقة المحدد غير صالح',

            'receipt.required' => '.ملف إيصال الدفع مطلوب',
            'receipt.file' => '.يجب أن يكون الإيصال ملفًا صالحًا',
            'receipt.mimes' => '.يجب أن يكون الإيصال صورة بصيغة (jpeg, png, jpg)',
            'receipt.max' => '.يجب ألا يتجاوز حجم الإيصال 2 ميغابايت',
        ];
    }

    // Configure the validator instance.
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $userId = auth()->id();
            
            // Check if user already has an active subscription
            $activeSubscription = $this->subscribingService->getCurrentActiveSubscription($userId);
            
            if ($activeSubscription) {
                $validator->errors()->add(
                    'subscription', '.لديك بالفعل اشتراك نشط. يرجى الانتظار حتى ينتهي قبل طلب اشتراك جديد'
                );
            }
            
            // Also check if user already has a pending request
            $pendingRequest = $this->subscriptionRequestRepository->checkPendingByUserId($userId);
            
            if ($pendingRequest) {
                $validator->errors()->add(
                    'subscription', '.لديك بالفعل طلب اشتراك قيد المعالجة. يرجى الانتظار حتى تتم معالجته'
                );
            }
        });
    }
}