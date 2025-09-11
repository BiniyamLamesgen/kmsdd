<?php

namespace App\Actions\Project;

use App\Models\Project;

class BulkDestroyProjectAction
{
    public function execute(array $ids)
    {
        $count = Project::whereIn('id', $ids)->delete();
        return [
            'success' => $count > 0,
            'message' => $count > 0 ? 'Projects deleted successfully.' : 'No projects deleted.',
        ];
    }
}
