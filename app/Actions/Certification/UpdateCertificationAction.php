<?php

namespace App\Actions\Certification;

use App\Models\Certification;
use Illuminate\Support\Facades\Storage;

class UpdateCertificationAction
{
    public function execute(string $id, array $data)
    {
        $certification = \App\Models\Certification::find($id);
        if (!$certification) {
            return ['success' => false, 'message' => 'Certification not found.'];
        }

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            // Remove old image if exists
            if ($certification->image) {
                Storage::disk('public')->delete($certification->image);
            }
            $path = $data['image']->store('certifications', 'public');
            $data['image'] = $path;
        } else {
            unset($data['image']);
        }

        $certification->update($data);

        return ['success' => true, 'certification' => $certification, 'message' => 'Certification updated successfully.'];
    }
}
