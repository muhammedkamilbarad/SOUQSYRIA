<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehicleModel;
use App\Services\VehicleModelService;
use App\Http\Requests\VehicleModelRequest;

class VehicleModelController extends Controller
{
    protected $vehicleModelService;

    public function __construct(VehicleModelService $vehicleModelService)
    {
        $this->vehicleModelService = $vehicleModelService;
    }

    public function index()
    {
        $vehicleModels = $this->vehicleModelService->getAllVehicleModels();
        return response()->json($vehicleModels, 200);
    }

    public function store(VehicleModelRequest $request)
    {
        $vehicleModel = $this->vehicleModelService->createVehicleModel($request->all());
        return response()->json($vehicleModel, 201);
    }

    public function show(int $id)
    {
        $vehicleModel = $this->vehicleModelService->getVehicleModelById($id);
        if (!$vehicleModel)
        {
            return response()->json(['message' => 'Vehicle model not found'], 404);
        }
        return response()->json($vehicleModel, 200);
    }

    public function update(VehicleModelRequest $request, int $id)
    {
        $vehicleModel = $this->vehicleModelService->getVehicleModelById($id);
        if (!$vehicleModel)
        {
            return response()->json(['message' => 'Vehicle model not found'], 404);
        }
        $vehicleModel = $this->vehicleModelService->updateVehicleModel($vehicleModel, $request->all());
        return response()->json($vehicleModel, 200);
    }

    public function destroy($id)
    {
        $vehicleModel = $this->vehicleModelService->getVehicleModelById($id);
        if (!$vehicleModel)
        {
            return response()->json(['message' => 'Vehicle model not found'], 404);
        }
        $this->vehicleModelService->deleteVehicleModel($vehicleModel);
        return response()->json(['message' => 'Vehicle model deleted successfully'], 200);
    }

    public function getVehicleModelsByBrandId(int $brandId)
    {
        
        try {
            $vehicleModles = $this->vehicleModelService->getVehicleModelsByBrandId($brandId);
            return response()->json([
                'success' => true,
                'data'    => $vehicleModles,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
