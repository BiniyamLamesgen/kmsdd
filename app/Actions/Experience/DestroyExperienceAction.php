<?php

namespace App\Actions\Experience;

use App\Models\Experience;

class DestroyExperienceAction
{
    public function execute(string $id)
    {
        $experience = Experience::find($id);
        if (!$experience) {
            return ['success' => false, 'message' => 'Experience not found.'];
        }
        $experience->delete();
        return ['success' => true, 'message' => 'Experience deleted successfully.'];
    }
}
