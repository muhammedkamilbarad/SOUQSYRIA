<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Services\RoleService;
use App\Http\Requests\RoleRequest;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index()
    {
        $roles = $this->roleService->getAllRoles();
        return response()->json($roles, 200);
    }

    public function store(RoleRequest $request)
    {
        $role = $this->roleService->createRole($request->all());
        return response()->json($role, 201);
    }
    
    public function show(int $id)
    {
        $role = $this->roleService->getRoleById($id);
        if (!$role)
        {
            return response()->json(['message' => 'Role not found'], 404);
        }
        return response()->json($role, 200);
    }

    public function update(RoleRequest $request, int $id)
    {
        $role = $this->roleService->getRoleById($id);
        if (!$role)
        {
            return response()->json(['message' => 'Role not found'], 404);
        }
        $role = $this->roleService->updateRole($role, $request->all());
        return response()->json($role, 200);
    }

    public function destroy(int $id)
    {
        $role = $this->roleService->getRoleById($id);
        if (!$role)
        {
            return response()->json(['message' => 'Role not found'], 404);
        }
        $this->roleService->deleteRole($role);
        return response()->json(['message' => 'Role deleted successfully'], 200);
    }
}
