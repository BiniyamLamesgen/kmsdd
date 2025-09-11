<?php

namespace App\Actions\Gallery;

use App\Models\Gallery;

class BulkDestroyGalleryAction
{
    public function execute(array $ids)
    {
        $count = Gallery::whereIn('id', $ids)->delete();
        return [
            'success' => $count > 0,
            'message' => $count > 0 ? 'Gallery images deleted successfully.' : 'No gallery images deleted.',
        ];
    }
}
