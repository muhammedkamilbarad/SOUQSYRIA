<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FeatureService;
use App\Http\Requests\FeatureRequest;


class FeatureController extends Controller
{
    protected $featureService;

    public function __construct(FeatureService $featureService)
    {
        $this->featureService = $featureService;
    }

    public function index()
    {
        $feature = $this->featureService->getAllFeatures();
        return response()->json($feature, 200);
    }

    public function store(FeatureRequest $request)
    {
        $feature = $this->featureService->createFeature($request->all());
        return response()->json($feature, 201);
    }

    public function show(int $id)
    {
        $feature = $this->featureService->getFeatureById($id);
        if(!$feature) {
            return response()->json(['message' => 'Feature not found'], 404);
        }
        return response()->json($feature, 200);
    }

    public function update(FeatureRequest $request, int $id)
    {
        $feature = $this->featureService->getFeatureById($id);
        if(!$feature) {
            return response()->json(['message' => 'Feature not found'], 404);
        }
        $feature = $this->featureService->updateFeature($feature, $request->all());
        return response()->json($feature, 200);
    }

    public function destroy(int $id)
    {
        $feature = $this->featureService->getFeatureById($id);
        if(!$feature) {
            return response()->json(['message' => 'Feature not found'], 404);
        }
        $this->featureService->deleteFeature($feature);
        return response()->json(['message' => 'Feature deleted successfully'], 200);
    }
}
