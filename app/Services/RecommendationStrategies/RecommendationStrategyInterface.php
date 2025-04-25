<?php

namespace App\Services\RecommendationStrategies;

use App\Models\Advertisement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

interface RecommendationStrategyInterface
{
    // Get similar advertisements based on the given advertisement
    public function getSimilarAdvertisements(Advertisement $advertisement, Builder $query, int $limit): Collection;
    
    // Add necessary relations to the query
    public function addRelationsToQuery(Builder $query): Builder;
}