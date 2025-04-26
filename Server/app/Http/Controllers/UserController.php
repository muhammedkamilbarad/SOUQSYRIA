<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\UserService;
use App\Http\Requests\UserRequest;
use App\Http\Requests\ProfileRequest;
use App\Services\SubscribingService;
use App\Services\FavoriteService;

class UserController extends Controller
{
    protected $userService;
    protected $subscriptionService;
    protected $favoriteService;

    public function __construct(UserService $userService, 
                                SubscribingService $subscriptionService, 
                                FavoriteService $favoriteService)
    {
        $this->userService = $userService;
        $this->subscriptionService = $subscriptionService;
        $this->favoriteService = $favoriteService;
    }

    public function index(Request $request)
    {
        $filters = $this->extractFilters($request);
        $searchTerms = $this->extractSearchTerms($request);

        // Extract pagination parameters
        $page = (int) $request->input('page', 1);
        $perPage = (int) $request->input('per_page', 15);

        $users = $this->userService->getAllUsers($filters, $searchTerms, $page, $perPage);
        return response()->json($users, 200);
    }

    protected function extractFilters(Request $request)
    {
        return [
            'role' => $request->input('role'),
            'created_at_from' => $request->input('created_at_from'),
            'created_at_to' => $request->input('created_at_to'),
            'updated_at_from' => $request->input('updated_at_from'),
            'updated_at_to' => $request->input('updated_at_to'),
            'deleted_at_from' => $request->input('deleted_at_from'),
            'deleted_at_to' => $request->input('deleted_at_to'),
            'email_verified_at_from' => $request->input('email_verified_at_from'),
            'email_verified_at_to' => $request->input('email_verified_at_to'),
        ];
    }

    protected function extractSearchTerms(Request $request)
    {
        return [
            'search' => $request->input('search'),
        ];
    }

    public function store(UserRequest $request)
    {
        $user = $this->userService->createUser($request->all());
        return response()->json($user, 201);
    }

    public function show(int $id)
    {
        $user = $this->userService->getUserWithRoleById($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user, 200);
    }

    public function update(UserRequest $request, int $id)
    {
        $user = $this->userService->getUserWithRoleById($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user = $this->userService->updateUser($user, $request->all());
        return response()->json($user, 200);
    }

    public function destroy(int $id)
    {
        $user = $this->userService->getUserWithRoleById($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $this->userService->deleteUser($user);
        return response()->json(['message' => 'User deleted successfully'], 200);
    }
    public function softDelete(int $id)
    {
        $user = $this->userService->getUserById($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $this->userService->softDeleteUser($user);
        return response()->json(['message' => 'User soft deleted successfully'], 200);
    }


    public function restore(int $id)
    {
        $user = $this->userService->restoreUser(new User(), $id);

        if (!$user) {
            return response()->json(['message' => 'User not found or not deleted'], 404);
        }

        return response()->json(['message' => 'User restored successfully', 'user' => $user], 200);
    }

    public function getProfile(Request $request)
    {
        try {
            $user = $request->user();
            $user = $this->userService->getUserById($user->id);

            $subscription = $this->subscriptionService->getCurrentActiveSubscription($user->id);

            return response()->json([
                'user' => $user,
                'subscription' => $subscription ? $subscription : '.لا يوجد اشتراك نشط حالياً',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => '.فشل في جلب بيانات الملف الشخصي',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getUserPermissions(Request $request)
    {
        $user = $request->user();
        $user = $this->userService->getUserWithRoleById($user->id);
        return response()->json($user->role, 200);
    }

    public function getAuthMe(Request $request)
    {
        $user = $request->user();
        $user = $this->userService->getUserWithRoleById($user->id);

        return response()->json([
            'profile' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'is_verified' => $user->is_verified,
                'email_verified_at' => $user->email_verified_at,
                'image' => $user->image,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
            'role' => [
                'id' => $user->role->id,
                'name' => $user->role->name,
                'permissions' => $user->role->permissions->pluck('name'),
            ],
        ], 200);
    }

    public function updateProfile(ProfileRequest $request)
    {
        $user = $request->user();
        $user = $this->userService->getUserById($user->id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user = $this->userService->updateProfile($user, $request->validated());
        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => $user,
        ], 200);
    }
}
