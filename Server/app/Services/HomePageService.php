<?php

namespace App\Services;

use App\Models\Advertisement;
use App\Models\PopularQuestion;
use App\Enums\CategoryType;
use App\Http\Resources\HomePageAdvertisementCollection;
use App\Repositories\AdvertisementRepository;

class HomePageService
{

    protected AdvertisementRepository $advertisementRepository;

    public function __construct(AdvertisementRepository $advertisementRepository)
    {
        $this->advertisementRepository = $advertisementRepository;
    }
    public function getHomePageData(): array
    {
        return [
            'advertisements' => new HomePageAdvertisementCollection($this->getLatestAdvertisementsPerCategory()),
            'advertisement_count_per_category' => $this->getAdvertisementCountPerCategory(),
            'advertisement_count_per_city' => $this->getAdvertisementCountPerCity(),
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
            ->with($this->advertisementRepository->getCommonRelations())
            ->latest()
            ->take(6)
            ->get();
            $advertisements[$category->name] = $ads;
        }
        return $advertisements;
    }

    private function getAdvertisementCountPerCategory(): array
    {
        return Advertisement::where('ads_status', 'accepted')
            ->where('active_status', 'active')
            ->selectRaw('category_id, COUNT(*) as count')
            ->groupBy('category_id')
            ->pluck('count', 'category_id')
            ->toArray();
    }

    private function getAdvertisementCountPerCity(): array
    {
        return Advertisement::where('ads_status', 'accepted')
            ->where('active_status', 'active')
            ->selectRaw('city, COUNT(*) as count')
            ->groupBy('city')
            ->pluck('count', 'city')
            ->toArray();
    }

    private function getPopularQuestionsPerCategory(): array
    {
        return PopularQuestion::where('status', true)
            ->select('question', 'answer', 'priority')
            ->get()
            ->toArray();
    }
}
