<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Services\PackageService;
use App\Http\Requests\PackagesRequest;

class PackageController extends Controller
{
    protected $packageService;

    public function __construct(PackageService $packageService)
    {
        $this->packageService = $packageService;
    }

    public function index()
    {
        $packages = $this->packageService->getAllPackages();
        return response()->json($packages);
    }

    public function store(PackagesRequest $request)
    {
        $package = $this->packageService->createPackage($request->all());
        return response()->json($package, 201);
    }

    public function show(int $id)
    {
        $package = $this->packageService->getPackageById($id);
        if (!$package) {
            return response()->json(['message' => 'Package not found'], 404);
        }
        return response()->json($package);
    }

    public function update(PackagesRequest $request, int $id)
    {
        $package = $this->packageService->getPackageById($id);
        if (!$package) {
            return response()->json(['message' => 'Package not found'], 404);
        }
        $package = $this->packageService->updatePackage($package, $request->all());
        return response()->json($package);
    }

    public function destroy(int $id)
    {
        $package = $this->packageService->getPackageById($id);
        if (!$package) {
            return response()->json(['message' => 'Package not found'], 404);
        }
        $this->packageService->deletePackage($package);
        return response()->json(['message' => 'Package deleted successfully']);
    }
}
