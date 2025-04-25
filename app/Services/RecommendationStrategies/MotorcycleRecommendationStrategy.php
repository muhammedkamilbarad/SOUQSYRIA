<?php

namespace App\Services\RecommendationStrategies;

use App\Models\Advertisement;
use App\Repositories\AdvertisementRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class MotorcycleRecommendationStrategy implements RecommendationStrategyInterface
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

        // Extract vehicle and car specific attributes
        $brandId = $advertisement->vehicleAdvertisement->brand_id ?? null;
        $modelId = $advertisement->vehicleAdvertisement->model_id ?? null;
        $year = $advertisement->vehicleAdvertisement->year ?? null;
        $motorcycleType = $advertisement->motorcycleAdvertisement->motorcycle_type ?? null;
        $type = $advertisement->type ?? null;

        // Log::info('Query count: ' . $query->count());
        
        if ($query->count() > 4000)
        {
            // Apply filters to the query before getting results
            if ($brandId) {
                $query->whereHas('vehicleAdvertisement', function($q) use ($brandId) {
                    $q->where('brand_id', $brandId);
                });
            }

            // Apply car type filter if available
            if ($motorcycleType) {
                $query->whereHas('motorcycleAdvertisement', function($q) use ($motorcycleType) {
                    $q->where('motorcycle_type', $motorcycleType);
                });
            }

            // Apply type filter (sale or rent)
            $query->where('type', $type);
        }
        else if ($query->count() > 500)
        {
            // Apply car type filter if available
            if ($motorcycleType) {
                $query->whereHas('motorcycleAdvertisement', function($q) use ($motorcycleType) {
                    $q->where('motorcycle_type', $motorcycleType);
                });
            }

            // Apply type filter (sale or rent)
            $query->where('type', $type);
        }
        

        // Log::info('Query count: ' . $query->count());

        // Apply eager loading for better performance
        $candidates = $query->get();
        
        // Combine results
        return $this->scoreCandidates($candidates, $advertisement, $brandId, $modelId, $year, $motorcycleType, $limit);
    }
    
    private function scoreCandidates(Collection $candidates, Advertisement $advertisement, ?int $brandId, ?int $modelId, int $year, ?string $motorcycleType, int $limit): Collection
    {
        return $candidates->map(function ($candidate) use (
            $advertisement,
            $brandId,
            $modelId,
            $year,
            $motorcycleType
        ) {
            $score = 0;
            
            // Brand match
            $candidateBrandId = $candidate->vehicleAdvertisement->brand_id ?? null;
            if ($brandId && $candidateBrandId && $candidateBrandId === $brandId) {
                $score += 25;
                
                // Model match - only relevant if brands match
                $candidateModelId = $candidate->vehicleAdvertisement->model_id ?? null;
                if ($modelId && $candidateModelId && $candidateModelId === $modelId) {
                    $score += 15;
                }
            }
            
            // Motorcycle type match
            $candidateMotorcycleType = $candidate->motorcycleAdvertisement->motorcycle_type ?? null;
            if ($motorcycleType && $candidateMotorcycleType && $candidateMotorcycleType === $motorcycleType) {
                $score += 35;
            }
            
            // Type match (sale or rent)
            if ($candidate->type === $advertisement->type) {
                $score += 20;
            }
            
            // Year similarity
            $candidateYear = $candidate->vehicleAdvertisement->year ?? null;
            if ($year && $candidateYear) {
                $yearDiff = abs($candidateYear - $year);
                $score += max(0, 10 * (1 - min($yearDiff / 10, 1))); // Assuming max difference of 10 years
            }
            
            // Price similarity
            if (isset($candidate->price) && isset($advertisement->price) && $advertisement->price > 0) {
                $priceDiff = abs(($candidate->price - $advertisement->price) / $advertisement->price);
                $score += max(0, 10 * (1 - min($priceDiff, 1)));
            }
            
            $candidate->similarity_score = $score;
            return $candidate;
        })->sortByDesc('similarity_score')->take($limit);
    }
}