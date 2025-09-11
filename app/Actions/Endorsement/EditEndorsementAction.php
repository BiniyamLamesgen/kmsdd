<?php

namespace App\Actions\Endorsement;

use App\Models\Endorsement;

class EditEndorsementAction
{
    public function execute(string $id)
    {
        $endorsement = Endorsement::with('employee','skill','endorsedBy')->find($id);
        if (!$endorsement) {
            return ['success' => false, 'message' => 'Endorsement not found.'];
        }
        return ['success' => true, 'endorsement' => $endorsement];
    }
}
