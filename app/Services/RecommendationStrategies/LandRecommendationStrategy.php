<?php

namespace App\Services\RecommendationStrategies;

use App\Models\Advertisement;
use App\Repositories\AdvertisementRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class LandRecommendationStrategy implements RecommendationStrategyInterface
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
        $squareMeters = $advertisement->landAdvertisement->square_meters ?? null;
        
        // Get candidates from the same city
        $sameCityCandidates = $this->repository->getCandidatesFromSameCity($query, $advertisement->city);
        
        // Score same-city candidates
        $scoredSameCity = $this->scoreCandidates($sameCityCandidates, $advertisement, $squareMeters, 100, $limit);
        
        // If we have enough same-city candidates, return them
        if ($scoredSameCity->count() >= $limit) {
            return $scoredSameCity;
        }
        
        // Otherwise, get candidates from other cities
        $otherCityCandidates = $this->repository->getCandidatesFromOtherCities($query, $advertisement->city);
        
        // Score other-city candidates
        $scoredOtherCity = $this->scoreCandidates($otherCityCandidates, $advertisement, $squareMeters, 0, $limit - $scoredSameCity->count());
        
        // Combine results
        return $scoredSameCity->concat($scoredOtherCity)->take($limit);
    }
    
    private function scoreCandidates(Collection $candidates, Advertisement $advertisement, ?int $squareMeters, int $baseScore, int $limit): Collection
    {
        return $candidates->map(function ($candidate) use ($advertisement, $squareMeters, $baseScore) {
            $score = $baseScore; // Base score for city match
            
            // Calculate weight for type (rent, sale) match
            if ($candidate->type === $advertisement->type) {
                $score += 20;
            }
            
            // Calculate weight for square meters similarity
            $candidateSquareMeters = $candidate->landAdvertisement->square_meters ?? null;
            if ($squareMeters && $candidateSquareMeters && $squareMeters > 0) {
                $squareMetersDiff = abs(($candidateSquareMeters - $squareMeters) / $squareMeters);
                $score += max(0, 10 * (1 - min($squareMetersDiff, 1)));
            }
            
            // Calculate price similarity
            if (isset($candidate->price) && isset($advertisement->price) && $advertisement->price > 0) {
                $priceDiff = abs(($candidate->price - $advertisement->price) / $advertisement->price);
                $score += max(0, 10 * (1 - min($priceDiff, 1)));
            }
            
            $candidate->similarity_score = $score;
            return $candidate;
        })->sortByDesc('similarity_score')->take($limit);
    }
}