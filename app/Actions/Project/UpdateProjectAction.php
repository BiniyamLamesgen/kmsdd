<?php

namespace App\Actions\Project;

use App\Models\Project;

class UpdateProjectAction
{
    public function execute(string $id, array $data)
    {
        $project = Project::find($id);
        if (!$project) {
            return ['success' => false, 'message' => 'Project not found.'];
        }
        $project->update($data);
        return ['success' => true, 'project' => $project, 'message' => 'Project updated successfully.'];
    }
}
