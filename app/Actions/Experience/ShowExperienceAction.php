<?php

namespace App\Actions\Experience;

use App\Models\Experience;

class ShowExperienceAction
{
    public function execute(string $id)
    {
        $experience = Experience::find($id);
        if (!$experience) {
            return ['success' => false, 'message' => 'Experience not found.'];
        }
        return ['success' => true, 'experience' => $experience];
    }
}
