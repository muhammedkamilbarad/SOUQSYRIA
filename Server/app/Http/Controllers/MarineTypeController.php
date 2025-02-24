<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MarineType;
use App\Services\MarineTypeService;
use App\Http\Requests\MarineTypeRequest;

class MarineTypeController extends Controller
{
    protected $marineTypeService;

    public function __construct(MarineTypeService $marineTypeService)
    {
        $this->marineTypeService = $marineTypeService;
    }

    public function index()
    {
        $MarineTypes = $this->marineTypeService->getAllMarineTypes();
        return response()->json($MarineTypes, 200);
    }

    public function store(MarineTypeRequest $request)
    {
        $MarineType = $this->marineTypeService->createMarineType($request->all());
        return response()->json($MarineType, 201);
    }

    public function show(int $id)
    {
        $MarineType = $this->marineTypeService->getMarineTypeById($id);
        if(!$MarineType) {
            return response()->json(['message' => 'MarineType not found'], 404);
        }
        return response()->json($MarineType, 200);
    }

    public function update(MarineTypeRequest $request, int $id)
    {
        $MarineType = $this->marineTypeService->getMarineTypeById($id);
        if(!$MarineType) {
            return response()->json(['message' => 'MarineType not found'], 404);
        }
        $MarineType = $this->marineTypeService->updateMarineType($MarineType, $request->all());
        return response()->json($MarineType, 200);
    }

    public function destroy(int $id)
    {
        $MarineType = $this->marineTypeService->getMarineTypeById($id);
        if(!$MarineType) {
            return response()->json(['message' => 'MarineType not found'], 404);
        }
        $this->marineTypeService->deleteMarineType($MarineType);
        return response()->json(['message' => 'MarineType deleted successfully'], 200);
    }
}