<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Advertisement;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Bus\Batchable;
use Illuminate\Support\Facades\DB;

class ProcessAdvertisementImageUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public $timeout = 120;
    public $tries = 5;
    public $backoff = [5, 10, 20, 40, 60];
    /**
     * Create a new job instance.
     */
    public function __construct(private int $advertisementId, private string $tempPath)
    {
        
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if($this->batch() && $this->batch()->cancelled())
        {
            return;
        }
        try {
            DB::transaction(function () {
                $advertisement = Advertisement::findOrFail($this->advertisementId);
                $imageUrl = $this->uploadToDigitalOcean($advertisement);
                $advertisement->images()->create([
                    'url' => $imageUrl,
                    'advs_id' => $advertisement->id
                ]);
                Log::info("Image uploaded successfully for advertisement #{$this->advertisementId}", [
                    'image_url' => $imageUrl
                ]);
            });
            $this->cleanupTempFile();
        } catch (\Exception $e) {
            Log::error("Failed to upload image for advertisement #{$this->advertisementId}", [
                'error' => $e->getMessage(),
                'temp_path' => $this->tempPath
            ]);
            $advertisement->update(['image_upload_status' => 'failed']);
            $this->cleanupTempFile();
            throw $e;
        }
    }

    private function uploadToDigitalOcean(Advertisement $advertisement): string
    {
        try {
            $originalExtension = $this->detectFileExtension($this->tempPath);
            $fileName = $this->generateUniqueFileName($advertisement, $originalExtension);
            if(Str::contains($fileName, 'R'))
            {
                Log::error('File Name with R letter :: '.$fileName);
                throw new \Exception('Upload to DigitalOcean Spaces failed');
            }
            $path = "advertisements/{$advertisement->id}/{$fileName}";
            try {
                $uploadResult = Storage::disk('s3')->put($path, file_get_contents($this->tempPath), 'public');
                if (!$uploadResult) {
                    throw new \Exception('Upload to DigitalOcean Spaces failed');
                }
            } catch (\Aws\S3\Exception\S3Exception $e) {
                throw new \Exception("DigitalOcean upload failed: " . $e->getMessage());
            }
            $imageUrl = Storage::disk('s3')->url($path);
            return $imageUrl;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function detectFileExtension(string $filePath): string
    {
        $mimeType = mime_content_type($filePath);
        $mimeToExtension = [
            'image/jpeg' => 'jpeg',
            'image/png' => 'png',
            'image/jpg' => 'jpg',
        ];
        return $mimeToExtension[$mimeType] ?? 'jpg';
    }

    private function generateUniqueFileName(Advertisement $advertisement, string $extension): string
    {
        return time() . '-' . Str::slug($advertisement->title) . '-' . Str::random(10) . '.' . $extension;
    }

    private function cleanupTempFile(): void
    {
        if(file_exists($this->tempPath))
        {
            unlink($this->tempPath);
        }
    }
}
