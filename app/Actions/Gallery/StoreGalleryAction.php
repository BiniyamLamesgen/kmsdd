<?php

namespace App\Actions\Gallery;

use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class StoreGalleryAction
{
    public function execute(array $data, ?UploadedFile $image = null): array
    {
        try {
            // Debug logging
            Log::info('StoreGalleryAction Debug:', [
                'data_keys' => array_keys($data),
                'image_param' => $image ? 'File received' : 'No file',
                'image_info' => $image ? [
                    'name' => $image->getClientOriginalName(),
                    'size' => $image->getSize(),
                    'mime' => $image->getMimeType(),
                ] : null,
            ]);

            // Handle image upload
            if ($image && $image->isValid()) {
                $path = Storage::disk('public')->putFile('gallery_images', $image);
                $data['image'] = $path;

                Log::info('Gallery image uploaded:', [
                    'path' => $path,
                    'full_url' => Storage::disk('public')->url($path)
                ]);
            } else {
                Log::info('No valid gallery image to upload');
            }

            $gallery = Gallery::create($data);

            Log::info('Gallery created successfully:', [
                'gallery_id' => $gallery->id,
                'image' => $gallery->image ?? null
            ]);

            return [
                'success' => true,
                'message' => 'Gallery image created successfully.',
                'gallery' => $gallery,
            ];
        } catch (\Exception $e) {
            Log::error('StoreGalleryAction Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
