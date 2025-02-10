<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehicleModel;
use App\Services\CategoryService;
use App\Http\Requests\CategoryRequest;


class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    
    public function index()
    {
        $categories = $this->categoryService->getAllCategories();
        return response()->json($categories, 200);
    }

    public function store(CategoryRequest $request)
    {
        $category = $this->categoryService->createCategory($request->all());
        return response()->json($category, 201);
    }

    public function show(int $id)
    {
        $category = $this->categoryService->getCategoryById($id);
        if (!$category)
        {
            return response()->json(['message' => 'Category Not Found'], 404);
        }
        return response()->json($category, 200);
    }

    public function update(CategoryRequest $request, int $id)
    {
        $category = $this->categoryService->getCategoryById($id);
        if (!$category)
        {
            return response()->json(['message' => 'Category Not Found'], 404);
        }
        $category = $this->categoryService->updateCategory($category, $request->all());
        return response()->json($category, 200);
    }

    public function destroy(int $id)
    {
        $category = $this->categoryService->getCategoryById($id);
        if (!$category)
        {
            return response()->json(['message' => 'Category Not Found'], 404);
        }
        $this->categoryService->deleteCategory($category);
        return response()->json(['message' => 'Category Deleted'], 200);
    }
}
