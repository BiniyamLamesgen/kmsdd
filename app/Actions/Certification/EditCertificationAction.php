<?php

namespace App\Actions\Certification;

use App\Models\Certification;
use Illuminate\Support\Facades\Storage;

class EditCertificationAction
{
    public function execute(string $id)
    {
        $certification = Certification::find($id);
        
        if (!$certification) {
            return ['success' => false, 'message' => 'Certification not found.'];
        }
        
        // Ensure the URL is consistent by using request()->getSchemeAndHttpHost() which will give us "http://127.0.0.1:8000"
        // instead of "http://localhost"
        if ($certification->image) {
            $baseUrl = request()->getSchemeAndHttpHost();
            $certification->image_url = "{$baseUrl}/storage/{$certification->image}";
            
            // Log the URL being created for debugging
            \Log::info("Generated image URL in EditCertificationAction: {$certification->image_url}");
        } else {
            $certification->image_url = null;
        }
            
        return ['success' => true, 'certification' => $certification];
    }
}
