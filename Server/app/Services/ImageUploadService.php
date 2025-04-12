<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ImageUploadService
{
    public function uploadAdvertisementImages(int $advertisementId, array $images)
    {
        $path = "advertisements/{$advertisementId}";
        return $this->uploadImages($path, $images);
    }

    private function uploadImages(string $path, array $images)
    {
        $uploadedUrls = [];
        $uploadedPaths = [];
        try {
            foreach ($images as $image) {
                $extension = $image->getClientOriginalExtension();
                $filename = time() . '_' . Str::random(10) . '.' . $extension;
                $full_path = $path . '/' . $filename;
                $stored = Storage::disk('s3')->put($full_path, file_get_contents($image), 'public');
                if (!$stored) {
                    throw new \Exception("Image upload failed for {$filename}");
                }
                $uploadedUrls[] = ['url' => Storage::disk('s3')->url($full_path)];
                $uploadedPaths[] = $full_path;
            }
            return $uploadedUrls;
        } catch (\Exception $e) {
            $files = Storage::disk('s3')->files($path);
            Storage::disk('s3')->delete($files);
            throw $e;
        }
    }

    public function uploadImage(string $path_name, $image): string
    {
        try {
            $extension = $image->getClientOriginalExtension();
            $fileName = time() . '-' . Str::random(10) . '.' . $extension;
            $path = "{$path_name}/{$fileName}";
            $stored = Storage::disk('s3')->put($path, file_get_contents($image), 'public');
            if (!$stored) {
                throw new \Exception("Image upload failed");
            }
            return Storage::disk('s3')->url($path);
        } catch (\Exception $e) {
            Storage::disk('s3')->delete($path);
            Log::error("Image upload error: {$e->getMessage()}");
            throw $e;
        }
    }
}
