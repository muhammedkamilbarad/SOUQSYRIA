<?php

namespace App\Services;

use App\Jobs\ProcessAdvertisementImageUpload;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use App\Models\Advertisement;
use Illuminate\Support\Facades\Log;

class AdvertisementImageService
{
    public function uploadImageBatch(Advertisement $advertisement, array $images)
    {
        $advertisement->update(['image_upload_status' => 'processing']);
        $jobs = $this->prepareImageUploadJobs($advertisement, $images);
        return Bus::batch($jobs)
        ->then(function () use ($advertisement) {
            $advertisement->update(['image_upload_status' => 'completed']);
            Log::info("All images uploaded successfully for advertisement", [
                'advertisement_id' => $advertisement->id
            ]);
        })
        ->catch(function () use ($advertisement) {
            $advertisement->update(['image_upload_status' => 'failed']);
            Log::error("Some images failed to upload for advertisement", [
                'advertisement_id' => $advertisement->id
            ]);
        })
        ->dispatch();
    }

    private function prepareImageUploadJobs(Advertisement $advertisement, array $images)
    {
        $jobs = [];
        foreach($images as $image)
        {
            $tempPath = tempnam(sys_get_temp_dir(), 'adv_image');
            file_put_contents($tempPath, file_get_contents($image));
            $jobs[] = new ProcessAdvertisementImageUpload($advertisement->id, $tempPath);
        }
        return $jobs;
    }

}
