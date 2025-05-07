<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\PopularQuestion;
use App\Services\PopularQuestionService;
use App\Http\Requests\PopularQuestionRequest;

class PopularQuestionController extends Controller
{
    protected $popularQuestionService;

    public function __construct(PopularQuestionService $popularQuestionService)
    {
        $this->popularQuestionService = $popularQuestionService;
    }

    public function index()
    {
        $PopularQuestion = $this->popularQuestionService->getAllPopularQuestions();
        return response()->json($PopularQuestion, 200);
    }

    public function getAllActive(): JsonResponse
    {
        $popularQuestion = $this->popularQuestionService->getAllActivePopularQuestions();
        if ($popularQuestion) {
            return response()->json([
                'success'=> true,
                'data'=> $popularQuestion,
            ], 200);
        } else {
            return response()->json([
                'success'=> true,
                'message'=> '.لا يوجد اي أسئلة شائعة متاحة للعرض حاليا',
            ], 200);
        }
    }

    public function store(PopularQuestionRequest $request)
    {
        $PopularQuestion = $this->popularQuestionService->createPopularQuestion($request->all());
        return response()->json($PopularQuestion, 201);
    }

    public function show(int $id)
    {
        $PopularQuestion = $this->popularQuestionService->getPopularQuestionById($id);
        if (!$PopularQuestion) {
            return response()->json(['message' => 'Question not found'], 404);
        }
        return response()->json($PopularQuestion, 200);
    }

    public function update(PopularQuestionRequest $request, int $id)
    {
        $PopularQuestion = $this->popularQuestionService->getPopularQuestionById($id);
        if (!$PopularQuestion) {
            return response()->json(['message' => 'Question not found'], 404);
        }
        $PopularQuestion = $this->popularQuestionService->updatePopularQuestion($PopularQuestion, $request->all());
        return response()->json($PopularQuestion, 200);
    }

    public function destroy(int $id)
    {
        $PopularQuestion = $this->popularQuestionService->getPopularQuestionById($id);
        if (!$PopularQuestion) {
            return response()->json(['message' => 'Question not found'], 404);
        }
        $this->popularQuestionService->deletePopularQuestion($PopularQuestion);
        return response()->json(['message' => 'Question deleted successfully'], 200);
    }
}