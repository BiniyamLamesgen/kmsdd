<?php

namespace App\Actions\Experience;

class StoreExperienceAction
{
    public function execute(array $data)
    {
        $action = new CreateExperienceAction();
        return $action->execute($data);
    }
}
