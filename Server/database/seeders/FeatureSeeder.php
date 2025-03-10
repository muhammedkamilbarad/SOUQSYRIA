<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\FeatureGroup;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    public function run()
    {
        // Get feature groups
        $vehicleGroup = FeatureGroup::where('name', 'Vehicle Features')->first();
        $houseGroup = FeatureGroup::where('name', 'House Features')->first();
        $landGroup = FeatureGroup::where('name', 'Land Features')->first();
        $marineGroup = FeatureGroup::where('name', 'Marine Features')->first();
        $generalGroup = FeatureGroup::where('name', 'General Features')->first();
        
        // Vehicle features
        $vehicleFeatures = [
            'Air Conditioning', 'Bluetooth', 'Cruise Control', 'Navigation System',
            'Parking Sensors', 'Backup Camera', 'Leather Seats', 'Sunroof/Moonroof',
            'Heated Seats', 'Blind Spot Monitoring', 'Lane Departure Warning',
            'Keyless Entry', 'Premium Sound System', 'Automatic Emergency Braking'
        ];
        
        // House features
        $houseFeatures = [
            'Central Air', 'Garage', 'Swimming Pool', 'Garden', 'Balcony',
            'Basement', 'Fireplace', 'Security System', 'Elevator', 'Gym',
            'Smart Home Features', 'Energy Efficient', 'Furnished', 'Pet Friendly'
        ];
        
        // Land features
        $landFeatures = [
            'Utilities Available', 'Road Access', 'Waterfront', 'Mountain View',
            'Agricultural', 'Commercial Zoning', 'Residential Zoning', 'Flat Land',
            'Wooded', 'River Access', 'Lakefront'
        ];
        
        // Marine features
        $marineFeatures = [
            'Life Jackets', 'Navigation Equipment', 'Fishing Equipment', 'Trailer Included',
            'Depth Finder', 'Radio', 'Fresh Water System', 'Shore Power', 'Galley',
            'Sleeping Quarters'
        ];
        
        // General features
        $generalFeatures = [
            'Recently Renovated', 'New Construction', 'Warranty Included', 'Financing Available',
            'Premium Package', 'Custom Features', 'Premium Location'
        ];
        
        // Create all features with their respective group IDs
        $this->createFeatures($vehicleFeatures, $vehicleGroup->id);
        $this->createFeatures($houseFeatures, $houseGroup->id);
        $this->createFeatures($landFeatures, $landGroup->id);
        $this->createFeatures($marineFeatures, $marineGroup->id);
        $this->createFeatures($generalFeatures, $generalGroup->id);
    }
    
    /**
     * Create features with the specified group ID
     */
    private function createFeatures($features, $groupId)
    {
        foreach ($features as $feature) {
            Feature::create([
                'name' => $feature,
                'feature_group_id' => $groupId
            ]);
        }
    }
}