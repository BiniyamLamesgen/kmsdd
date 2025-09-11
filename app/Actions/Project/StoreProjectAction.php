<?php

namespace App\Actions\Project;

use App\Models\Project;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class StoreProjectAction
{
    public function execute(array $data, ?UploadedFile $document = null): array
    {
        try {
            if ($document) {
                $data['document'] = Storage::disk('public')->putFile('project_documents', $document);
            }
            $project = Project::create($data);
            return [
                'success' => true,
                'message' => 'Project created successfully.',
                'project' => $project,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
