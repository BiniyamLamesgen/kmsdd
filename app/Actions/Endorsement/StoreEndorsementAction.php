<?php

namespace App\Actions\Endorsement;

use App\Models\Endorsement;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class StoreEndorsementAction
{
    public function execute(array $data, ?UploadedFile $document = null): array
    {
        try {
            if ($document) {
                $data['document'] = Storage::disk('public')->putFile('endorsement_documents', $document);
            }
            $endorsement = Endorsement::create($data);
            return [
                'success' => true,
                'message' => 'Endorsement created successfully.',
                'endorsement' => $endorsement,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
