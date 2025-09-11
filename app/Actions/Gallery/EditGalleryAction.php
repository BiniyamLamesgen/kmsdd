<?php

namespace App\Actions\Gallery;

use App\Models\Gallery;
use App\Http\Resources\GalleryResource;

class EditGalleryAction
{
    public function execute(string $id)
    {
        $gallery = Gallery::find($id);
        if (!$gallery) {
            return ['success' => false, 'message' => 'Gallery image not found.'];
        }
        return ['success' => true, 'gallery' => new GalleryResource($gallery)];
    }
}
