<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Color;
use App\Services\ColorService;
use App\Http\Requests\ColorRequest;

class ColorController extends Controller
{
    protected $colorService;

    public function __construct(ColorService $colorService)
    {
        $this->colorService = $colorService;
    }

    public function index()
    {
        $colors = $this->colorService->getAllColors();
        return response()->json($colors, 200);
    }

    public function store(ColorRequest $request)
    {
        $color = $this->colorService->createColor($request->all());
        return response()->json($color, 201);
    }

    public function show(int $id)
    {
        $color = $this->colorService->getColorById($id);
        if(!$color) {
            return response()->json(['message' => 'Color not found'], 404);
        }
        return response()->json($color, 200);
    }

    public function update(ColorRequest $request, int $id)
    {
        $color = $this->colorService->getColorById($id);
        if(!$color) {
            return response()->json(['message' => 'Color not found'], 404);
        }
        $color = $this->colorService->updateColor($color, $request->all());
        return response()->json($color, 200);
    }

    public function destroy(int $id)
    {
        $color = $this->colorService->getColorById($id);
        if(!$color) {
            return response()->json(['message' => 'Color not found'], 404);
        }
        $this->colorService->deleteColor($color);
        return response()->json(['message' => 'Color deleted successfully'], 200);
    }
}
