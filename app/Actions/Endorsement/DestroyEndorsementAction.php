<?php

namespace App\Actions\Endorsement;

use App\Models\Endorsement;

class DestroyEndorsementAction
{
    public function execute(string $id)
    {
        $endorsement = Endorsement::find($id);
        if (!$endorsement) {
            return ['success' => false, 'message' => 'Endorsement not found.'];
        }
        $endorsement->delete();
        return ['success' => true, 'message' => 'Endorsement deleted successfully.'];
    }
}
