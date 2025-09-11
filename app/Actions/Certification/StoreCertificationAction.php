<?php

namespace App\Actions\Certification;

use App\Models\Certification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class StoreCertificationAction
{
    public function execute(array $data): array
    {
        try {
            // Handle image upload if present
            if (isset($data['image']) && $data['image']) {
                $imagePath = $data['image']->store('certifications', 'public');
                $data['image'] = $imagePath;
                
                // Log for debugging
                Log::info("Certification image stored at: $imagePath");
                
                // Verify the file exists after upload
                if (!Storage::disk('public')->exists($imagePath)) {
                    Log::error("Certification image was not saved properly: $imagePath");
                }
            }

            $certification = Certification::create($data);
            
            return [
                'success' => true,
                'certification' => $certification,
                'message' => 'Certification created successfully.',
            ];
        } catch (\Exception $e) {
            Log::error('Error creating certification: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Error creating certification: ' . $e->getMessage(),
            ];
        }
    }
}
