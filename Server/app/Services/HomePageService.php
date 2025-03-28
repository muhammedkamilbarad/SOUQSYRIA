<?php

namespace App\Services;

use App\Models\Advertisement;
use App\Models\PopularQuestion;
use App\Enums\CategoryType;

class HomePageService
{

    public function getHomePageData(): array
    {
        return [
            'advertisements' => $this->getLatestAdvertisementsPerCategory(),
            'popular_questions' => $this->getPopularQuestionsPerCategory(),
        ];
    }


    private function getLatestAdvertisementsPerCategory(): array
    {
        $advertisements = [];
        foreach (CategoryType::cases() as $category) {
            $ads = Advertisement::where('category_id', $category->value)
            ->where('ads_status', 'accepted')
            ->where('active_status', 'active')
            ->with(['images' => function($query){
                $query->limit(1);
            }])
            ->latest()
            ->take(6)
            ->get();
            $advertisements[$category->name] = $ads;
        }
        return $advertisements;
    }


    private function getPopularQuestionsPerCategory(): array
    {
        return PopularQuestion::where('priority', 'High')
            ->where('status', true)
            ->select('category', 'question', 'answer')
            ->groupBy('category', 'question', 'answer')
            ->get()
            ->groupBy('category')
            ->map(function ($items) {
                return $items->first();
            })->toArray();
    }
}
