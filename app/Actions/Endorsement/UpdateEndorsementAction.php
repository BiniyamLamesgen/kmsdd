<?php

namespace App\Actions\Endorsement;

use App\Models\Endorsement;

class UpdateEndorsementAction
{
    public function execute(string $id, array $data)
    {
        $endorsement = Endorsement::find($id);
        if (!$endorsement) {
            return ['success' => false, 'message' => 'Endorsement not found.'];
        }
        $endorsement->update($data);
        return ['success' => true, 'endorsement' => $endorsement, 'message' => 'Endorsement updated successfully.'];
    }
}
