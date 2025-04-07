<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehicleModel;
use App\Services\VehicleBrandService;
use App\Http\Requests\VehicleBrandRequest;
use App\Http\Requests\GetBrandByCategoryRequest;

class VehicleBrandController extends Controller
{
    protected $vehicleBrandService;

    public function __construct(VehicleBrandService $vehicleBrandService)
    {
        $this->vehicleBrandService = $vehicleBrandService;
    }

    public function index()
    {
        $vehicleBrands = $this->vehicleBrandService->getAllVehicleBrands();
        return response()->json($vehicleBrands, 200);
    }

    public function store(VehicleBrandRequest $request)
    {
        $vehicleBrand = $this->vehicleBrandService->createVehicleBrand($request->all());
        return response()->json($vehicleBrand, 201);
    }

    public function show(int $id)
    {
        $vehicleBrand = $this->vehicleBrandService->getVehicleBrandById($id);
        if (!$vehicleBrand)
        {
            return response()->json(['message' => 'Vehicle brand not found'], 404);
        }
        return response()->json($vehicleBrand, 200);
    }

    public function update(VehicleBrandRequest $request, int $id)
    {
        $vehicleBrand = $this->vehicleBrandService->getVehicleBrandById($id);
        if (!$vehicleBrand)
        {
            return response()->json(['message' => 'Vehicle brand not found'], 404);
        }
        $vehicleBrand = $this->vehicleBrandService->updateVehicleBrand($vehicleBrand, $request->all());
        return response()->json($vehicleBrand, 200);
    }

    public function destroy($id)
    {
        $vehicleBrand = $this->vehicleBrandService->getVehicleBrandById($id);
        if (!$vehicleBrand)
        {
            return response()->json(['message' => 'Vehicle brand not found'], 404);
        }
        $this->vehicleBrandService->deleteVehicleBrand($vehicleBrand);
        return response()->json(['message' => 'Vehicle brand deleted successfully'], 200);
    }

    public function getBrandsByCategory(GetBrandByCategoryRequest $request)
    {
        try
        {
            \Log::info("In Controller");
            $brands = $this->vehicleBrandService->getBrandsByCategory($request->validated());
            \Log::info('Brands => '. $brands);
            return response()->json([
                'success' => true,
                'data' => $brands
            ], 200);
        } catch (\Exception $e)
        {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching vehicle brands.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
