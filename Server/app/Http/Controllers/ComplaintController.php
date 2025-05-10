<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ComplaintRequest;
use App\Http\Requests\ComplainAdvertisementRequest;
use App\Services\ComplaintService;

class ComplaintController extends Controller
{
    protected $complaintService;

    public function __construct(ComplaintService $complaintService)
    {
        $this->complaintService = $complaintService;
        $this->middleware('auth'); // This is for ensuring user is authenticated
    }

    public function complaintAboutAdvertisement(ComplainAdvertisementRequest $request)
    {
        $userId = auth()->id(); // getting the user who is authenticated

        $data = [
            'content' => $request->content,
            'advs_id' => $request->advs_id,
            'user_id' => $userId,
        ];

        if ($this->complaintService->checkComplaintExistence($userId, $data['advs_id']))
        {
            return response()->json([
                'success' => true,
                'message' => '.لقد قمت بالابلاغ على هذا الإعلان من قبل',
            ], 200);
        }

        $complaint = $this->complaintService->addComplaint($data);

        return response()->json([
            'success' => true,
            'message' => 'Complaint has been sent.',
            'data' => $complaint,
        ], 201);
    }

    public function getAllComplaintsForUser()
    {
        $userId = auth()->id(); // getting the user who is authenticated
        $complaints = $this->complaintService->getAllComplaintsForUser($userId);

        return response()->json([
            'success' => true,
            'message' => 'All complaints for user.',
            'data' => $complaints,
        ], 200);
    }

    public function getComplaintsForAdvertisement(int $advertisementId)
    {
        $complaints = $this->complaintService->getComplaintsForAdvertisement($advertisementId);

        return response()->json([
            'success' => true,
            'message' => 'Complaints for advertisement.',
            'data' => $complaints,
        ], 200);
    }


    public function destroy(int $id)
    {
        $complaint = $this->complaintService->getComplaintById($id);
        if(!$complaint) {
            return response()->json(['message' => '.هذا الإبلاغ غير موجود'], 404);
        }
        $this->complaintService->deleteComplaint($complaint);
        return response()->json(['message' => '.تم حذف الإعلان بنجاح'], 200);
    }

    public function index(Request $request)
    {
        try{
            $perPage = $request->get('per_page', 5);
            $adv_id = $request->get('adv_id');
            $complaints = $this->complaintService->getAllComplaints($perPage, $adv_id);
            return response()->json([
                'success' => true,
                'data' => [
                    'current_page' => $complaints->currentPage(),
                    'per_page' => $complaints->perPage(),
                    'total' => $complaints->total(),
                    'total_pages' => $complaints->lastPage(),
                    'next_page' => $complaints->nextPageUrl() ? $complaints->currentPage() + 1 : null,
                    'complaints' => $complaints->items()
                ]
            ], 200);
        }catch (Exception $e) {
            Log::error('Failed to fetch complaints: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch complaints'
            ], 500);
        }
    }
}
