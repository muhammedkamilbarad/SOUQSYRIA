<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Services\PermissionService;
use App\Http\Requests\PermissionRequest;

class PermissionController extends Controller
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function index()
    {
        $permissions = $this->permissionService->getAllPermissions();
        return response()->json($permissions);
    }

    public function store(PermissionRequest $request)
    {
        $permissions = $this->permissionService->createPermission($request->all());
        return response()->json($permissions, 201);
    }

    public function show(int $id)
    {
        $permission = $this->permissionService->getPermissionById($id);
        if(!$permission) {
            return response()->json(['message' => 'Permission not found'], 404);
        }
        return response()->json($permission);
    }

    public function update(PermissionRequest $request, int $id)
    {
        $permission = $this->permissionService->getPermissionById($id);
        if(!$permission) {
            return response()->json(['message' => 'Permission not found'], 404);
        }
        $permission = $this->permissionService->updatePermission($permission, $request->all());
        return response()->json($permission);
    }

    public function destroy(int $id)
    {
        $permission = $this->permissionService->getPermissionById($id);
        if(!$permission) {
            return response()->json(['message' => 'Permission not found'], 404);
        }
        $this->permissionService->deleteColor($permission);
        return response()->json(['message' => 'Permission deleted successfully']);
    }
}
