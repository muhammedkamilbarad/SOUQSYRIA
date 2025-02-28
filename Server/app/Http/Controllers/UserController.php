<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\UserService;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $users = $this->userService->getAllUsers();
        return response()->json($users, 200);
    }

    public function store(UserRequest $request)
    {
        $user = $this->userService->createUser($request->all());
        return response()->json($user, 201);
    }

    public function show(int $id)
    {
        $user = $this->userService->getUserById($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user, 200);
    }

    public function update(UserRequest $request, int $id)
    {
        $user = $this->userService->getUserById($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user = $this->userService->updateUser($user, $request->all());
        return response()->json($user, 200);
    }

    public function destroy(int $id)
    {
        $user = $this->userService->getUserById($id);
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
}
