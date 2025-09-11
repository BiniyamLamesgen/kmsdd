<?php

namespace App\Actions\Experience;

use App\Models\Experience;

class UpdateExperienceAction
{
    public function execute(string $id, array $data)
    {
        $experience = Experience::find($id);
        if (!$experience) {
            return ['success' => false, 'message' => 'Experience not found.'];
        }
        $experience->update($data);
        return ['success' => true, 'experience' => $experience, 'message' => 'Experience updated successfully.'];
    }
}
