<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SystemComplaintRequest;
use App\Services\SystemComplaintService;
use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Database\QueryException;
use Log;

class SystemComplaintController extends Controller
{
    protected $systemComplaintService;

    public function __construct(SystemComplaintService $systemComplaintService)
    {
        $this->systemComplaintService = $systemComplaintService;
    }

    // Display a listing of the system complaints.
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 5);
            $systemComplaints = $this->systemComplaintService->getSystemComplaints($perPage);
            return response()->json([
                'success' => true,
                'message' => 'System complaints fetched successfully',
                'data' => [
                    'current_page' => $systemComplaints->currentPage(),
                    'per_page' => $systemComplaints->perPage(),
                    'total' => $systemComplaints->total(),
                    'total_pages' => $systemComplaints->lastPage(),
                    'next_page' => $systemComplaints->nextPageUrl() ? $systemComplaints->currentPage() + 1 : null,
                    'system complaints' => $systemComplaints->items()
                ]
            ], 200);
        } catch (Exception $e) {
            Log::error('Failed to fetch system complaints: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch system complaints'
            ], 500);
        }
    }

    // Store a newly created system complaint in storage.
    public function store(SystemComplaintRequest $request)
    {
        try {
            $systemComplaint = $this->systemComplaintService->createSystemComplaint($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'System complaint created successfully',
                'data' => $systemComplaint
            ], 200);
        } catch (QueryException $e) {
            Log::error('Database error when creating system complaint: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create system complaint due to database error'
            ], 500);
        } catch (Exception $e) {
            Log::error('Failed to create system complaint: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create system complaint'
            ], 500);
        }
    }

    // Display the specified system complaint.
    public function show($id)
    {
        try {
            $systemComplaint = $this->systemComplaintService->getSystemComplaintById($id);

            if (!$systemComplaint) {
                return response()->json([
                    'success' => false,
                    'message' => 'System complaint not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'System complaint fetched successfully',
                'data' => $systemComplaint
            ], 200);
        } catch (Exception $e) {
            Log::error('Failed to fetch system complaint: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch system complaint'
            ], 500);
        }
    }

    // Update the specified system complaint in storage.
    public function update(SystemComplaintRequest $request, $id)
    {
        try {
            $systemComplaint = $this->systemComplaintService->getSystemComplaintById($id);

            if (!$systemComplaint) {
                return response()->json([
                    'success' => false,
                    'message' => 'System complaint not found'
                ], 404);
            }

            $updatedSystemComplaint = $this->systemComplaintService->updateSystemComplaint($systemComplaint, $request->validated());
            return response()->json([
                'success' => true,
                'message' => 'System complaint updated successfully',
                'data' => $updatedSystemComplaint
            ], 200);
        } catch (QueryException $e) {
            Log::error('Database error when updating system complaint: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update system complaint due to database error'
            ], 500);
        } catch (Exception $e) {
            Log::error('Failed to update system complaint: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update system complaint'
            ], 500);
        }
    }

    // Remove the specified system complaint from storage.
    public function destroy($id)
    {
        try {
            $systemComplaint = $this->systemComplaintService->getSystemComplaintById($id);

            if (!$systemComplaint) {
                return response()->json([
                    'success' => false,
                    'message' => 'System complaint not found'
                ], 404);
            }

            $this->systemComplaintService->deleteSystemComplaint($systemComplaint);
            return response()->json([
                'success' => true,
                'message' => 'System complaint deleted successfully'
            ], 200);
        } catch (QueryException $e) {
            Log::error('Database error when deleting system complaint: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete system complaint due to database error'
            ], 500);
        } catch (Exception $e) {
            Log::error('Failed to delete system complaint: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete system complaint'
            ], 500);
        }
    }
}
