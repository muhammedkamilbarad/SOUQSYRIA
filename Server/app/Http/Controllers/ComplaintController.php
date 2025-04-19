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

        $complaint = $this->complaintService->addComplaint($data);

        return response()->json([
            'success' => true,
            'message' => 'Complaint has been sent',
            'data' => $complaint,
        ], 201);
    }

    public function getAllComplaintsForUser()
    {
        $userId = auth()->id(); // getting the user who is authenticated
        $complaints = $this->complaintService->getAllComplaintsForUser($userId);

        return response()->json([
            'success' => true,
            'message' => 'All complaints for user',
            'data' => $complaints,
        ], 200);
    }

    public function getComplaintsForAdvertisement(int $advertisementId)
    {
        $complaints = $this->complaintService->getComplaintsForAdvertisement($advertisementId);

        return response()->json([
            'success' => true,
            'message' => 'Complaints for advertisement',
            'data' => $complaints,
        ], 200);
    }


    public function destroy(int $id)
    {
        $complaint = $this->complaintService->getComplaintById($id);
        if(!$complaint) {
            return response()->json(['message' => 'Complaint not found'], 404);
        }
        $this->complaintService->deleteComplaint($complaint);
        return response()->json(['message' => 'Complaint deleted successfully'], 200);
    }

    public function index()
    {
        $complaints = $this->complaintService->getAllComplaints();

        return response()->json([
            'success' => true,
            'data' => $complaints,
        ], 200);
    }
}
