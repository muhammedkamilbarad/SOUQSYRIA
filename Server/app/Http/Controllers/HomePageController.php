<?php

namespace App\Http\Controllers;
use App\Services\HomePageService;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    protected HomePageService $homePageService;

    public function __construct(HomePageService $homePageService)
    {
        $this->homePageService = $homePageService;
    }

    public function index()
    {
        $data = $this->homePageService->getHomePageData();
        return response()->json($data);
    }
}
