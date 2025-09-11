<?php

namespace App\Actions\Gallery;

use App\Models\Gallery;

class DestroyGalleryAction
{
    public function execute(string $id)
    {
        $gallery = Gallery::find($id);
        if (!$gallery) {
            return ['success' => false, 'message' => 'Gallery image not found.'];
        }
        $gallery->delete();
        return ['success' => true, 'message' => 'Gallery image deleted successfully.'];
    }
}
