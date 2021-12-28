<?php

namespace tf\models\actions;

class AcceptAction extends AbstractAction
{
    public const CODE = 'accept';

    public function getTitle(): string
    {
        return 'Выполнено';
    }

    public function getInnerTitle(): string
    {
        return self::CODE;
    }

    public function checkPermissions(int $userID, int $customerID, int $performerID): bool
    {
        return $userID === $customerID;
    }
}
