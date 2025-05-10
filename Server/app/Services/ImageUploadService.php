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
        try {
            foreach ($images as $image) {
                $extension = $image->getClientOriginalExtension();
                $filename = time() . '_' . Str::random(10) . '.' . $extension;
                $full_path = $path . '/' . $filename;
                $stored = Storage::disk('s3')->put($full_path, file_get_contents($image), 'public');
                if (!$stored) {
                    throw new \Exception("فشل تحميل الصور");
                }
                $uploadedUrls[] = ['url' => Storage::disk('s3')->url($full_path)];
            }
            return $uploadedUrls;
        } catch (\Exception $e) {
            $files = Storage::disk('s3')->files($path);
            Storage::disk('s3')->delete($files);
            throw new \Exception("فشل تحميل الصور");
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
                throw new \Exception("فشل تحميل الصورة");
            }
            return Storage::disk('s3')->url($path);
        } catch (\Exception $e) {
            Storage::disk('s3')->delete($path);
            throw new \Exception("فشل تحميل الصورة");
        }
    }

    public function deleteImage(string $imageUrl): bool
    {
        try {
            $url = parse_url($imageUrl);
            $path = ltrim($url['path'], '/');
            $bucketPrefix = env('AWS_BUCKET') . '/';
            if (strpos($path, $bucketPrefix) === 0) {
                $path = substr($path, strlen($bucketPrefix));
            }
            if (substr($path, -1) === '/' || !pathinfo($path, PATHINFO_EXTENSION)) {
                $deleted = Storage::disk('s3')->deleteDirectory($path);
            } else {
                $deleted = Storage::disk('s3')->delete($path);
            }
            if (!$deleted) {
                Log::warning("Failed to delete: {$path}");
                return false;
            }
            return true;
        } catch (\Exception $e) {
            Log::error("Deletion error: {$e->getMessage()}");
            throw $e;
        }
    }


    public function deleteAdvertisementImages(int $advertisementId)
    {
        try {
            $path = "advertisements/{$advertisementId}";
            $files = Storage::disk('s3')->files($path);
            Storage::disk('s3')->delete($files);
        } catch (\Exception $e) {
            throw new \Exception("فشل حذف الصور");
        }
    }


    public function deleteSomeAdvertisementImages(int $advertisementId, array $images)
    {
        foreach ($images as $image) {
            try {
                $path = ltrim(parse_url($image['url'], PHP_URL_PATH), '/');
                Storage::disk('s3')->delete($path);
            } catch (\Exception $e) {
                throw new \Exception("فشل تحديث الصور");
            }
        }
    }

}
