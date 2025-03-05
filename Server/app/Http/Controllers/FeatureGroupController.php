<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FeatureGroupService;
use App\Http\Requests\FeatureGroupRequest;


class FeatureGroupController extends Controller
{
    protected $featureGroupService;

    public function __construct(FeatureGroupService $featureGroupService)
    {
        $this->featureGroupService = $featureGroupService;
    }

    public function index()
    {
        $featureGroup = $this->featureGroupService->getAllFeatureGroups();
        return response()->json($featureGroup, 200);
    }

    public function store(FeatureGroupRequest $request)
    {
        $featureGroup = $this->featureGroupService->createFeatureGroup($request->all());
        return response()->json($featureGroup, 201);
    }

    public function show(int $id)
    {
        $featureGroup = $this->featureGroupService->getFeatureGroupById($id);
        if(!$featureGroup) {
            return response()->json(['message' => 'Feature Group not found'], 404);
        }
        return response()->json($featureGroup, 200);
    }

    public function update(FeatureGroupRequest $request, int $id)
    {
        $featureGroup = $this->featureGroupService->getFeatureGroupById($id);
        if(!$featureGroup) {
            return response()->json(['message' => 'Feature Group not found'], 404);
        }
        $updatedfeatureGroup = $this->featureGroupService->updateFeatureGroup($featureGroup, $request->all());
        return response()->json($updatedfeatureGroup, 200);
    }

    public function destroy(int $id)
    {
        $featureGroup = $this->featureGroupService->getFeatureGroupById($id);
        if(!$featureGroup) {
            return response()->json(['message' => 'Feature Group not found'], 404);
        }
        $this->featureGroupService->deleteFeatureGroup($featureGroup);
        return response()->json(['message' => 'Feature Group deleted successfully'], 200);
    }

    public function categoryFeatuers(int $categoryId)
    {
        $featureGroup = $this->featureGroupService->getFeaturesWithCategoryId($categoryId);
        return response()->json($featureGroup, 200);
    }


}
