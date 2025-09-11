<?php

namespace App\Actions\HeroSlide;

use App\Models\HeroSlide;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BulkDeleteHeroSlideAction {
    public function execute(array $ids): array {
        $deleted = 0;
        $failed = 0;
        $failedTitles = [];
        $deletedTitles = [];

        foreach ($ids as $id) {
            try {
                $slide = HeroSlide::find($id);
                if ($slide) {
                    $title = $slide->title;
                    // Remove image from storage if exists
                    if ($slide->image && Storage::disk('public')->exists($slide->image)) {
                        Storage::disk('public')->delete($slide->image);
                    }
                    if ($slide->delete()) {
                        $deleted++;
                        $deletedTitles[] = $title;
                    } else {
                        $failed++;
                        $failedTitles[] = $title;
                    }
                } else {
                    $failed++;
                }
            } catch (\Exception $e) {
                $failed++;
                Log::error('Bulk delete error for HeroSlide ID ' . $id . ': ' . $e->getMessage(), [
                    'hero_slide_id' => $id,
                    'exception' => $e
                ]);
            }
        }

        return [
            'deleted' => $deleted,
            'failed' => $failed,
            'deleted_titles' => $deletedTitles,
            'failed_titles' => $failedTitles,
            'success' => $deleted > 0,
            'message' => $this->generateMessage($deleted, $failed)
        ];
    }

    private function generateMessage(int $deleted, int $failed): string {
        if ($deleted > 0 && $failed === 0) {
            return "Successfully deleted {$deleted} hero slide(s).";
        } elseif ($deleted > 0 && $failed > 0) {
            return "Partially completed: {$deleted} deleted, {$failed} failed to delete.";
        } else {
            return "Failed to delete hero slides. Please try again.";
        }
    }
}
