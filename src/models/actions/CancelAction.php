<?php

namespace tf\models\actions;

class CancelAction extends AbstractAction
{
    public const CODE = 'cancel';

    public function getTitle(): string
    {
        return 'Отменить задание';
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
