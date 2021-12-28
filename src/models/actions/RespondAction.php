<?php

namespace tf\models\actions;

class RespondAction extends AbstractAction
{
    public function getTitle(): string
    {
        return 'Откликнуться';
    }

    public function getInnerTitle(): string
    {
        return 'respond';
    }

    public function checkPermissions(int $userID, int $customerID, int $performerID): bool
    {
        return $userID === $performerID;
    }
}
