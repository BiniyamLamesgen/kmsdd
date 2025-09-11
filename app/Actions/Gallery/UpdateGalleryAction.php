<?php

namespace App\Actions\Gallery;

use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Http\Resources\GalleryResource;

class UpdateGalleryAction
{
    public function execute(string $id, array $data, ?UploadedFile $image = null)
    {
        $gallery = Gallery::find($id);
        if (!$gallery) {
            return ['success' => false, 'message' => 'Gallery image not found.'];
        }

        // Handle image upload
        if ($image) {
            // Remove old image if exists
            if ($gallery->image && Storage::disk('public')->exists($gallery->image)) {
                Storage::disk('public')->delete($gallery->image);
            }
            $data['image'] = Storage::disk('public')->putFile('gallery_images', $image);
        } else {
            // If no new image, do not overwrite existing image
            unset($data['image']);
        }

        $gallery->update($data);

        return [
            'success' => true,
            'gallery' => new GalleryResource($gallery),
            'message' => 'Gallery image updated successfully.'
        ];
    }
}
