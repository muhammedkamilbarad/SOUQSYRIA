<?php

namespace App\Services\RecommendationStrategies;

use App\Models\Advertisement;
use App\Repositories\AdvertisementRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class HouseRecommendationStrategy implements RecommendationStrategyInterface
{
    private AdvertisementRepository $repository;
    
    
    public function __construct(AdvertisementRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function addRelationsToQuery(Builder $query): Builder
    {
        return $query->with($this->repository->getCommonRelations());
    }
    
    public function getSimilarAdvertisements(Advertisement $advertisement, Builder $query, int $limit): Collection
    {

        // Extract house-specific attributes
        $houseType = $advertisement->houseAdvertisement->house_type ?? null;
        $roomCount = $advertisement->houseAdvertisement->number_of_rooms ?? null;
        
        // Get candidates from the same city
        $sameCityCandidates = $this->repository->getCandidatesFromSameCity($query, $advertisement->city);
        
        // Score same-city candidates
        $scoredSameCity = $this->scoreCandidates($sameCityCandidates, $advertisement, $houseType, $roomCount, 60, $limit);
        
        // If we have enough same-city candidates, return them
        if ($scoredSameCity->count() >= $limit) {
            return $scoredSameCity;
        }
        
        // Otherwise, get candidates from other cities
        $otherCityCandidates = $this->repository->getCandidatesFromOtherCities($query, $advertisement->city);
        
        // Score other-city candidates
        $scoredOtherCity = $this->scoreCandidates($otherCityCandidates, $advertisement, $houseType, $roomCount, 0, $limit - $scoredSameCity->count());
        
        // Combine results
        return $scoredSameCity->concat($scoredOtherCity)->take($limit);
    }
    
    private function scoreCandidates(Collection $candidates, Advertisement $advertisement, ?string $houseType, ?int $roomCount, int $baseScore, int $limit): Collection
    {
        return $candidates->map(function ($candidate) use ($advertisement, $houseType, $roomCount, $baseScore) {
            $score = $baseScore; // Base score for city match
            
            // House type match
            $candidateHouseType = $candidate->houseAdvertisement->house_type ?? null;
            if ($houseType && $candidateHouseType && $candidateHouseType === $houseType) {
                $score += 30;
            }
            
            // Type match (sale or rent)
            if ($candidate->type === $advertisement->type) {
                $score += 20;
            }
            
            // Price similarity
            if (isset($candidate->price) && isset($advertisement->price) && $advertisement->price > 0) {
                $priceDiff = abs(($candidate->price - $advertisement->price) / $advertisement->price);
                $score += max(0, 10 * (1 - min($priceDiff, 1)));
            }
            
            // Room count similarity
            $candidateRoomCount = $candidate->houseAdvertisement->number_of_rooms ?? null;
            if ($roomCount && $candidateRoomCount) {
                $roomDiff = abs($candidateRoomCount - $roomCount);
                $score += max(0, 5 * (1 - min($roomDiff / 6, 1))); // Assuming max difference of 6 rooms
            }
            
            $candidate->similarity_score = $score;
            return $candidate;
        })->sortByDesc('similarity_score')->take($limit);
    }
}