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
            return response()->json(['message' => 'Vehicle Model Not Found'], 404);
        }
        return response()->json($vehicleModel, 200);
    }

    public function update(VehicleModelRequest $request, int $id)
    {
        $vehicleModel = $this->vehicleModelService->getVehicleModelById($id);
        if (!$vehicleModel)
        {
            return response()->json(['message' => 'Vehicle Model Not Found'], 404);
        }
        $vehicleModel = $this->vehicleModelService->updateVehicleModel($vehicleModel, $request->all());
        return response()->json($vehicleModel, 200);
    }

    public function destroy($id)
    {
        $vehicleModel = $this->vehicleModelService->getVehicleModelById($id);
        if (!$vehicleModel)
        {
            return response()->json(['message' => 'Vehicle Model Not Found'], 404);
        }
        $this->vehicleModelService->deleteVehicleModel($vehicleModel);
        return response()->json(['message' => 'Vehicle Model Deleted Successfully'], 200);
    }
}
