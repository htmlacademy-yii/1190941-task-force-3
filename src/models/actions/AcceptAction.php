<?php

namespace tf\models\actions;

class AcceptAction extends AbstractAction
{
    public const CODE = 'accept';

    public function getTitle(): string
    {
        return 'Завершить задание';
    }

    public function getInnerTitle(): string
    {
        return self::CODE;
    }

    public function checkPermission(int $userID, int $customerID, ?int $performerID): bool
    {
        return $userID === $customerID;
    }
}
